<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\{SystemSetting, Repost};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'type',
        'student_id',
        'employee_id',
        'avatar',
        'bio',
        'google_id',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'youtube_link',
        'tiktok_link',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

    public function getSchoolID()
    {
        if ($this->type === 'student' && $this->student) {
            return $this->student->StudentID;
        } elseif ($this->type === 'employee' && $this->employee) {
            return $this->employee->EmployeeID;
        }

        return null;
    }

    public function userPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public function getUserPermissions()
    {
        return $this->userPermissions()->get();
    }


    public function getCurrentPosition()
    {
        // Get the current school year ID from SystemSettings
        $currentSchoolYearId = SystemSetting::where('key', 'current_school_year_id')->value('value');

        // Check if the user is associated with an organization for the current school year
        $organizationMember = OrganizationMember::where('user_id', $this->id)
            ->where('school_year_id', $currentSchoolYearId)
            ->first();

        if ($organizationMember) {
            // Retrieve additional information about the user's role within the organization
            $role = OrganizationRole::where('id', $organizationMember->organization_role_id)
                ->with('permissions', 'organization') // Load permissions and organization data
                ->first();

            if ($role) {
                // Return the role information
                return $role;
            }
        }

        return null; // User is not associated with an organization for the current school year
    }

    public function getRolePermissions()
    {
        // Get the user's current position
        $currentPosition = $this->getCurrentPosition();

        if ($currentPosition) {
            // Fetch the user's organization membership for the current position
            $organizationMember = OrganizationMember::where('user_id', $this->id)
                ->where('organization_role_id', $currentPosition->id) // Use ->id to access the role ID
                ->first();

            if ($organizationMember) {
                // Retrieve the permissions associated with the user's role in the organization
                return $currentPosition->permissions;
            }
        }

        return collect([]); // Return an empty collection if the user doesn't have a current position or role permissions
    }

     // Define the dynamic attribute Users.fullname
     public function getFullnameAttribute()
     {
         return $this->getFullname();
     }

    public function getFullname()
    {
        if ($this->type === 'student' && $this->student) {
            return $this->student->FullName;
        } elseif ($this->type === 'employee' && $this->employee) {
            return $this->employee->FullName;
        }

        return null;
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function isVerified(): bool
    {
        return $this->status === 'member' && $this->email_verified_at !== null;
    }


    public function isOfficial(): bool
    {
        // Check if the user has the "have_official_icon" permission
        return $this->hasPermission('have_official_icon');
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'StudentID');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'EmployeeID');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
                    ->withPivot(['organization_role_id', 'school_year_id'])
                    ->using(OrganizationMember::class);
    }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'user_permissions');
    // }

    public function hasPermission(string $permissionName): bool
    {
        // Get the user's permissions
        $userPermissions = collect($this->getUserPermissions());

        // Get the role permissions
        $rolePermissions = collect($this->getRolePermissions());

        // Combine user and role permissions into a single collection
        $allPermissions = $userPermissions->concat($rolePermissions);

        // Check if the permission name exists in the merged permissions collection
        return $allPermissions->contains('name', $permissionName);
    }


    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function isFollowing($user)
    {
        return $this->following->contains($user);
    }

    public function isMutualFollow($user)
    {
        return $this->isFollowing($user) && $user->isFollowing($this);
    }

    // Define the relationship for posts (a user has many posts)
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'desc');
    }

    // Define the relationship for post revisions (a user has many post revisions)
    public function postRevisions()
    {
        return $this->hasMany(PostRevision::class);
    }

    public function countFollowing()
    {
        return $this->following->count();
    }

    /**
     * Give permissions to the user by replacing the existing permissions.
     *
     * @param array $permissions
     */
    public function givePermission(array $permissions)
    {
        // Find permission IDs based on the given permission names
        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id')->all();

        // Sync the user's permissions with the provided permission IDs
        $this->userPermissions()->sync($permissionIds);
    }



    /**
     * Revoke permissions from the user.
     *
     * @param string|array $permissions
     */
    public function revokePermissionTo($permissions)
    {
        // Ensure permissions is an array
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        // Detach the specified permissions
        $this->userPermissions()->detach($permissions);
    }

    public function notifications()
    {
        return $this->hasMany(UserPortalNotification::class, 'user_id');
    }

    public function unreadNotificationCount()
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->count();
    }

}