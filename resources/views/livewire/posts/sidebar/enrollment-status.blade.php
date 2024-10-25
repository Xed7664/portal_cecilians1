<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title text-uppercase pb-0 fs-5">
            <span>Enrollment Process</span>
            <i class="bx bx-info-circle float-end small bx-xm text-portal" data-bs-toggle="tooltip" data-bs-title="This flow shows your current enrollment status."></i>
        </h5>

        <!-- Enrollment Flow -->
        <div class="timeline">
            <!-- Step 1: Admission Process -->
            <div class="timeline-item {{ $student->isAdmissionComplete ? 'active' : '' }}">
                <i class="bx bx-check-circle timeline-icon"></i>
                <div class="timeline-content">
                    <h6>Admission Process</h6>
                    <p>Complete the admission requirements.</p>
                </div>
                <span class="timeline-arrow {{ $student->isAdmissionComplete ? 'arrow-active' : '' }}"></span>
            </div>

            <!-- Step 2: Document Submission -->
            <div class="timeline-item {{ $student->isDocumentSubmitted ? 'active' : '' }}">
                <i class="bx bx-file timeline-icon"></i>
                <div class="timeline-content">
                    <h6>Document Submission</h6>
                    <p>Submit required documents.</p>
                </div>
                <span class="timeline-arrow {{ $student->isDocumentSubmitted ? 'arrow-active' : '' }}"></span>
            </div>

            <!-- Step 3: Enrollment Processing -->
            <div class="timeline-item {{ $student->isEnrolled ? 'active' : '' }}">
                <i class="bx bx-book timeline-icon"></i>
                <div class="timeline-content">
                    <h6>Enrollment Processing</h6>
                    <p>Processing your enrollment.</p>
                </div>
                <span class="timeline-arrow {{ $student->isEnrolled ? 'arrow-active' : '' }}"></span>
            </div>

            <!-- Step 4: Schedule Assignment -->
            <div class="timeline-item {{ $student->isScheduleAssigned ? 'active' : '' }}">
                <i class="bx bx-calendar timeline-icon"></i>
                <div class="timeline-content">
                    <h6>Schedule Assignment</h6>
                    <p>Assigning your schedule.</p>
                </div>
                <span class="timeline-arrow {{ $student->isScheduleAssigned ? 'arrow-active' : '' }}"></span>
            </div>

            <!-- Step 5: Payment -->
            <div class="timeline-item {{ $student->isPaymentComplete ? 'active' : '' }}">
                <i class="bx bx-credit-card timeline-icon"></i>
                <div class="timeline-content">
                    <h6>Payment</h6>
                    <p>Complete your payment.</p>
                </div>
                <span class="timeline-arrow {{ $student->isPaymentComplete ? 'arrow-active' : '' }}"></span>
            </div>

            <!-- Step 6: Confirmation -->
            <div class="timeline-item {{ $student->isConfirmed ? 'active' : '' }}">
                <i class="bx bx-check-double timeline-icon"></i>
                <div class="timeline-content">
                    <h6>Confirmation</h6>
                    <p>Your enrollment is confirmed!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Timeline structure */
    .timeline {
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .timeline-item {
        display: flex;
        align-items: center;
        position: relative;
        padding: 1rem 0;
    }
    .timeline-item:not(:last-child) {
        margin-bottom: 1rem;
    }
    .timeline-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: #ddd;
    }
    .timeline-content {
        flex: 1;
        padding-left: 1rem;
    }

    /* Arrow between timeline items */
    .timeline-arrow {
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-left: 10px solid #ddd;
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Highlighting active steps */
    .timeline-item.active .timeline-icon {
        color: #0d6efd; /* Primary Bootstrap color */
    }
    .timeline-arrow.arrow-active {
        border-left-color: #0d6efd;
    }

    /* Animated CSS arrows */
    .timeline-item.active .timeline-icon {
        animation: bounce 1s infinite;
    }
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
</style>
