export interface Subject {
  subject_code: string;
  subject_name: string;
  faculty: string;
  attended: number;
  total: number;
  percentage: number;
}

export interface StudentDashboard {
  student_id: string;
  student_name: string;
  semester: string;
  branch: string;
  section: string;
  overall: {
    total: number;
    attended: number;
    percentage: number;
  };
  subjects: Subject[];
}
