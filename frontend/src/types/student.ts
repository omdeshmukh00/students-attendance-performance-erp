export interface Subject {
  subject_code: string
  subject_name: string
  faculty: string
  attended: number
  total: number
  percentage: number
}

export interface Overall {
  total: number
  attended: number
  percentage: number
}

export interface Eligibility {
  detained: boolean
  mse1: boolean
  mse2: boolean
  exam_form: boolean
  incentive: boolean
}

export interface StudentDashboard {
  student_id: string
  student_name: string
  semester: number
  branch: string
  section: string
  overall: Overall
  eligibility: Eligibility
  subjects: Subject[]
}