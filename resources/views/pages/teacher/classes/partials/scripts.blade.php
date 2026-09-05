<script>
function classDetailPage() {
    return {
        activeTab: '{{ $tab }}',
        importModalOpen: false,
        deleteModalOpen: false,
        studentModalOpen: false,
        loadingSummary: false,
        selectedStudent: {},
        studentModulesSummary: [],
        studentOverallAvg: null,
        studentKktpStatus: '',

        async fetchStudentSummary(studentId) {
            this.studentModalOpen = true;
            this.loadingSummary = true;
            this.selectedStudent = {};
            this.studentModulesSummary = [];

            try {
                const response = await fetch(`/teacher/classes/{{ $class->id }}/students/${studentId}/summary`);
                const data = await response.json();

                if (data.success) {
                    this.selectedStudent = data.student;
                    this.studentModulesSummary = data.modules_summary;
                    this.studentOverallAvg = data.overall_avg;
                    this.studentKktpStatus = data.kktp_status;
                }
            } catch (e) {
                console.error("Gagal mengambil rincian nilai siswa:", e);
            } finally {
                this.loadingSummary = false;
            }
        }
    }
}
</script>
