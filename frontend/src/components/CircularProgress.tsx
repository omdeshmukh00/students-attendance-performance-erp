type Subject = {
  subject_code: string;
  subject_name: string;
  faculty: string;
  attended: number;
  total: number;
  percentage: number;
};

type Props = {
  subject: Subject;
};

export default function SubjectCard({ subject }: Props) {

  const percent = Math.ceil(subject.percentage);

  const radius = 36;
  const stroke = 6;
  const normalizedRadius = radius - stroke * 2;
  const circumference = normalizedRadius * 2 * Math.PI;

  const strokeDashoffset =
    circumference - (percent / 100) * circumference;

  return (
    <div className="bg-white rounded-2xl shadow-md p-6 flex justify-between items-center min-h-[140px]">

      {/* LEFT SIDE */}
      <div>
        <h3 className="font-bold text-gray-900 text-lg">
          {subject.subject_code} — {subject.subject_name}
        </h3>

        <p className="text-gray-500 mt-1 text-sm">
          {subject.faculty}
        </p>
      </div>

      {/* RIGHT SIDE CIRCLE */}
      <div className="flex items-center gap-6">

        <div className="relative">
          <svg height={radius * 2} width={radius * 2}>

            {/* Background Circle */}
            <circle
              stroke="#e5e7eb"
              fill="transparent"
              strokeWidth={stroke}
              r={normalizedRadius}
              cx={radius}
              cy={radius}
            />

            {/* Progress Circle */}
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
              transform={`rotate(-90 ${radius} ${radius})`}
            />
          </svg>

          {/* Percent Text */}
          <div className="absolute inset-0 flex items-center justify-center font-bold text-sm text-gray-900">
            {percent}%
          </div>
        </div>

        {/* Lectures */}
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
