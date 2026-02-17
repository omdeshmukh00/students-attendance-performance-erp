// import type { Subject } from "../types/student";

type Props = {
  subject: {
    subject_code: string;
    subject_name: string;
    faculty: string;
    attended: number;
    total: number;
    percentage: number;
  };
};

export default function SubjectCard({ subject }: Props) {
  const percent = Math.ceil(subject.percentage);

  const radius = 34;
  const stroke = 6;
  const normalizedRadius = radius - stroke * 0.5;
  const circumference = normalizedRadius * 2 * Math.PI;
  const strokeDashoffset =
    circumference - (percent / 100) * circumference;

  return (
    <div className="bg-white rounded-2xl shadow-md p-6 flex justify-between items-center min-h-[140px]">

      {/* LEFT TEXT */}
      <div>
        <h3 className="font-bold text-gray-900 text-lg">
          {subject.subject_code} — {subject.subject_name}
        </h3>

        <p className="text-gray-500 mt-1 text-sm font-medium">
          {subject.faculty}
        </p>
      </div>

      {/* RIGHT SIDE */}
      <div className="flex items-center gap-6">

        {/* ⭐ CIRCULAR PROGRESS */}
        <div className="relative w-20 h-20 flex items-center justify-center">

          <svg height={radius * 2} width={radius * 2} className="rotate-[-90deg]">

            {/* Background ring */}
            <circle
              stroke="#e5e7eb"
              fill="transparent"
              strokeWidth={stroke}
              r={normalizedRadius}
              cx={radius}
              cy={radius}
            />

            {/* Progress ring */}
            <circle
              stroke="#2563eb"
              fill="transparent"
              strokeWidth={stroke}
              strokeDasharray={circumference + " " + circumference}
              style={{ strokeDashoffset }}
              strokeLinecap="round"
              r={normalizedRadius}
              cx={radius}
              cy={radius}
            />
          </svg>

          {/* ⭐ CENTER TEXT */}
          <div className="absolute text-blue-600 font-bold text-lg">
            {percent}%
          </div>

        </div>

        {/* LECTURES */}
        <div className="text-right">
          <p className="font-bold text-gray-900">
            {subject.attended}/{subject.total}
          </p>
          <p className="text-xs text-gray-500">Lectures</p>
        </div>

      </div>

    </div>
  );
}
