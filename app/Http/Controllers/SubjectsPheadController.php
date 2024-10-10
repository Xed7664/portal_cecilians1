<?php
    namespace App\Http\Controllers;
    use App\Models\Subject;
    use App\Models\YearLevel;
    use App\Models\Department;
    use App\Models\Employee;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class SubjectsPheadController extends Controller
    {
        public function index()
        {
            // Get the current user's department_id
            $userDepartmentId = Auth::user()->employee->department_id;
        
            // Filter subjects based on the user's department_id and archive_status
            $subjects = Subject::where('archive_status', 0)
                               ->where('department_id', $userDepartmentId)
                               ->paginate(20);
        
            $yearLevels = YearLevel::all();
            
            // Filter teachers based on the user's department
            $teachers = Employee::where('department_id', $userDepartmentId)->get();
            
            return view('phead.subjects.index', compact('subjects', 'yearLevels', 'teachers'));
        }
                public function store(Request $request)
        {
            $validatedData = $request->validate([
                'subject_code' => 'required|unique:subjects',
                'description' => 'required',
                'room_name' => 'required',
                'day' => 'required',
                'time' => 'required',
                'teacher_id' => 'required|exists:employees,id',
                'year_level_id' => 'required|exists:year_levels,id',
                'semester' => 'required|in:1st Semester,2nd Semester',
                'lec_units' => 'required|numeric',
                'lab_units' => 'required|numeric',
                'total_units' => 'required|numeric',
                'pre_requisite' => 'nullable',
                'total_hours' => 'required|numeric',
            ]);

            $teacher = Employee::findOrFail($validatedData['teacher_id']);
            $validatedData['instructor_name'] = $teacher->FullName;

            $validatedData['archive_status'] = 0;
            $validatedData['school_year_id'] = null;
            $validatedData['semester_id'] = null;
            $validatedData['amount'] = '0.00';
            $validatedData['department_id'] = Auth::user()->employee->department_id;

            Subject::create($validatedData);

            return redirect()->route('phead.subjects.index')->with('success', 'Subject created successfully.');
        }

        public function update(Request $request, Subject $subject)
        {
            $validatedData = $request->validate([
                'subject_code' => 'required|unique:subjects,subject_code,' . $subject->id,
                'description' => 'required',
                'room_name' => 'required',
                'day' => 'required',
                'time' => 'required',
                'teacher_id' => 'required|exists:employees,id',
                'year_level_id' => 'required|exists:year_levels,id',
                'semester' => 'required|in:1st Semester,2nd Semester',
                'lec_units' => 'required|numeric',
                'lab_units' => 'required|numeric',
                'total_units' => 'required|numeric',
                'pre_requisite' => 'nullable',
                'total_hours' => 'required|numeric',
            ]);
        
            $teacher = Employee::findOrFail($validatedData['teacher_id']);
            $validatedData['instructor_name'] = $teacher->FullName;
        
            $subject->update($validatedData);
        
            return redirect()->route('phead.subjects.show', $subject)->with('success', 'Subject updated successfully.');
        }
        
        public function show(Subject $subject)
        {
            $teachers = Employee::where('department_id', Auth::user()->employee->department_id)->get();
            $yearLevels = YearLevel::all();
            return view('phead.subjects.show', compact('subject', 'teachers', 'yearLevels'));
        }

        public function archive(Subject $subject)
        {
            $subject->update(['archive_status' => 1]);
            return redirect()->route('phead.subjects.index')->with('success', 'Subject archived successfully.');
        }

        public function archivedIndex()
        {
            $archivedSubjects = Subject::where('archive_status', 1)->paginate(20);
            return view('phead.archived-subjects', compact('archivedSubjects'));
        }

        public function restore(Subject $subject)
    {
        $subject->update(['archive_status' => 0]);
        return redirect()->route('phead.subjects.archived')->with('success', 'Subject restored successfully.');
    }
    

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('phead.subjects.archived')->with('success', 'Subject permanently deleted.');
    }
      
       


      
    }
