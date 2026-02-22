import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import SubjectCard from "../components/SubjectCard";
import type { StudentDashboard } from "../types/student";

export default function DashboardPage() {
  useEffect(() => {
    document.title = "Student - Dashboard";
  }, []);
  const { btId } = useParams();

  const [data, setData] = useState<StudentDashboard | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    console.log("Dashboard Mounted");

    if (!btId) {
      console.log("No BT ID");
      setLoading(false);
      return;
    }

    const id = btId.toUpperCase();
    console.log("Fetching Student:", id);

    fetch(`http://127.0.0.1:8000/api/student/${id}`, {
      cache: "no-store",
    })
      .then(async (res) => {
        console.log("Response Status:", res.status);

        if (!res.ok) {
          setData(null);
          return;
        }

        const json = await res.json();
        console.log("Student Data:", json);
        setData(json);
      })
      .catch((err) => {
        console.log("Fetch Error:", err);
        setData(null);
      })
      .finally(() => {
        console.log("Fetch Finished");
        setLoading(false);
      });
  }, [btId]);

  /* ===== LOADING ===== */
  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-white text-gray-500 text-lg font-semibold">
        Loading Dashboard...
      </div>
    );
  }

  /* ===== WHITE SCREEN IF NOT FOUND ===== */
  if (!data) {
    return <div className="min-h-screen bg-white"></div>;
  }

  return (
    <div className="min-h-screen bg-gray-100 px-6 md:px-10 py-10">

      {/* HEADER */}
      <div className="bg-white rounded-3xl shadow-lg p-10 mb-12">
        <h1 className="text-4xl md:text-5xl font-black text-gray-900">
          {data.student_name}
        </h1>
        <p className="text-sm bg-gray-200 inline-block px-3 py-1 rounded-md font-semibold mt-2">
          {data.student_id}
        </p>
        <p className="text-gray-600 font-semibold mt-3 text-lg">
          {data.branch} • Sem {data.semester} • {data.section}
        </p>
      </div>

{/* OVERALL + ELIGIBILITY */}
<h2 className="text-2xl font-bold text-gray-900 mb-5">
  Overall Attendance & Student Status
</h2>

<div className="flex flex-wrap gap-8 mb-12">

  {/* OVERALL CARD */}
  {(() => {
    const percent = data.overall.percentage;

    const radius = 40;
    const stroke = 7;
    const normalizedRadius = radius - stroke * 0.5;
    const circumference = normalizedRadius * 2 * Math.PI;
    const strokeDashoffset =
      circumference - (percent / 100) * circumference;

    return (
      <div className="bg-white rounded-2xl shadow-md p-8 w-[320px] flex items-center gap-6">

        {/* Circle */}
        <div className="relative w-24 h-24 flex items-center justify-center">

          <svg
            height={radius * 2}
            width={radius * 2}
            className="rotate-[-90deg]"
          >
            <circle
              stroke="#e5e7eb"
              fill="transparent"
              strokeWidth={stroke}
              r={normalizedRadius}
              cx={radius}
              cy={radius}
            />

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

          <div className="absolute text-blue-600 font-extrabold text-xl">
            {percent}%
          </div>
        </div>

        <div>
          <p className="text-gray-500 font-medium">Overall</p>
          <p className="text-sm text-gray-400 mt-1">
            Attendance Performance
          </p>
        </div>

      </div>
    );
  })()}

  {/* ELIGIBILITY CARD */}
  <div className="bg-white rounded-2xl shadow-md p-8 w-[320px]">

    <h3 className="text-lg font-bold text-gray-900 mb-4">
      Exam Eligibility
    </h3>

    <div className="space-y-2 text-sm font-semibold">

      <p className={`${data.eligibility.mse1 ? "text-green-600" : "text-red-500"}`}>
        MSE 1 : {data.eligibility.mse1 ? "Eligible" : "Not Eligible"}
      </p>

      <p className={`${data.eligibility.mse2 ? "text-green-600" : "text-red-500"}`}>
        MSE 2 : {data.eligibility.mse2 ? "Eligible" : "Not Eligible"}
      </p>

      <p className={`${data.eligibility.exam_form ? "text-green-600" : "text-red-500"}`}>
        Exam Form : {data.eligibility.exam_form ? "Allowed" : "Blocked"}
      </p>

      <p className={`${data.eligibility.incentive ? "text-green-600" : "text-gray-500"}`}>
        Incentive : {data.eligibility.incentive ? "Yes" : "No"}
      </p>

      {data.eligibility.detained && (
        <p className="text-red-600 font-bold pt-2">
          🚫 Student Detained
        </p>
      )}

    </div>

  </div>

</div>
        

      {/* SUBJECTS */}
      <h2 className="text-2xl font-extrabold text-gray-900 mb-8">
        Subjects
      </h2>

      <div className="grid md:grid-cols-2 xl:grid-cols-3 gap-10">
        {data.subjects
          .filter((s) => s.percentage > 0)
          .map((subject, index) => (
            <SubjectCard key={index} subject={subject} />
          ))}
      </div>

    </div>
  );
}
