import { useEffect, useState } from "react";

type Overview = {
  total_students: number;
  eligible_students: number;
  defaulters: number;
  average_attendance: number;
};

type BranchStat = {
  branch: string;
  students: number;
};

type SubjectStat = {
  subject_code: string;
  subject_name: string;
  faculty: string;
  percentage: number;
};

type Defaulter = {
  bt_id: string;
  name: string;
  percentage: number;
};

export default function AdminDashboard() {
  useEffect(() => {
    document.title = "Admin - Dashboard";
  }, []);

  const [overview, setOverview] = useState<Overview | null>(null);
  const [branches, setBranches] = useState<BranchStat[]>([]);
  const [subjects, setSubjects] = useState<SubjectStat[]>([]);
  const [defaulters, setDefaulters] = useState<Defaulter[]>([]);
  const [uploading, setUploading] = useState(false);

  /*
  =========================
  LOAD ALL DASHBOARD DATA
  =========================
  */

  const loadData = () => {

    fetch("http://127.0.0.1:8000/api/admin/overview")
      .then(res => res.json())
      .then(setOverview);

    fetch("http://127.0.0.1:8000/api/admin/branch-analytics")
      .then(res => res.json())
      .then(setBranches);

    fetch("http://127.0.0.1:8000/api/admin/subject-analytics")
      .then(res => res.json())
      .then((data) => {

        /* REMOVE BROKEN SUBJECT ROWS */
        const clean = data.filter((s: SubjectStat) =>
          s.subject_code &&
          s.subject_name &&
          s.subject_name.length > 3 &&
          !s.subject_name.includes("Additiona")
        );

        setSubjects(clean);
      });

    fetch("http://127.0.0.1:8000/api/admin/defaulters")
      .then(res => res.json())
      .then(setDefaulters);
  };

  useEffect(() => {
    loadData();
  }, []);

  /*
  =========================
  MULTIPLE CSV UPLOAD
  =========================
  */

  const handleCsvUpload = async (e: any) => {

    const files = e.target.files;

    if (!files.length) return;

    setUploading(true);

    let success = 0;

    for (let i = 0; i < files.length; i++) {

      const formData = new FormData();
      formData.append("file", files[i]);

      const res = await fetch(
        "http://127.0.0.1:8000/api/admin/upload",
        {
          method: "POST",
          body: formData
        }
      );

      if (res.ok) success++;
    }

    setUploading(false);

    alert(success + " CSV file(s) imported successfully ✅");

    loadData();
  };

  /*
  =========================
  AUTO CSV SYNC
  =========================
  */

  const syncCsv = async () => {

    try {

      await fetch(
        "http://127.0.0.1:8000/api/admin/sync",
        { method: "POST" }
      );

      alert("CSV Sync Completed Successfully ✅");

      loadData();

    } catch {

      alert("CSV Sync Failed ❌");

    }
  };

  if (!overview) {
    return (
      <div className="h-screen flex items-center justify-center text-gray-500">
        Loading dashboard...
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 p-8">

      {/* HEADER */}
      <div className="flex justify-between items-center mb-10">

        <div>
          <h1 className="text-4xl font-black">
            College ERP Dashboard
          </h1>

          <p className="text-gray-500 mt-1">
            Admin Analytics Panel
          </p>
        </div>

        <div className="flex gap-4">

          {/* CSV UPLOAD */}
          <label className="bg-green-600 text-white px-5 py-2 rounded-lg font-semibold cursor-pointer hover:bg-green-700">

            {uploading ? "Uploading..." : "Upload CSV"}

            <input
              type="file"
              accept=".csv"
              multiple
              onChange={handleCsvUpload}
              className="hidden"
            />
          </label>

          {/* AUTO SYNC */}
          <button
            onClick={syncCsv}
            className="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700"
          >
            Sync CSV
          </button>

        </div>

      </div>

      {/* STATS */}
      <div className="grid md:grid-cols-4 gap-6 mb-10">

        <div className="bg-white rounded-xl shadow p-6">
          <p className="text-gray-500 text-sm">
            Total Students
          </p>

          <h2 className="text-3xl font-bold mt-2">
            {overview.total_students}
          </h2>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <p className="text-gray-500 text-sm">
            Eligible Students
          </p>

          <h2 className="text-3xl font-bold mt-2 text-green-600">
            {overview.eligible_students}
          </h2>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <p className="text-gray-500 text-sm">
            Defaulters
          </p>

          <h2 className="text-3xl font-bold mt-2 text-red-500">
            {overview.defaulters}
          </h2>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <p className="text-gray-500 text-sm">
            Average Attendance
          </p>

          <h2 className="text-3xl font-bold mt-2 text-blue-600">
            {overview.average_attendance}%
          </h2>
        </div>

      </div>

      {/* BRANCH DISTRIBUTION */}
      <div className="bg-white rounded-xl shadow p-6 mb-10">

        <h2 className="text-xl font-bold mb-4">
          Branch Distribution
        </h2>

        <div className="space-y-3">

          {branches.map((b, i) => (

            <div
              key={i}
              className="flex justify-between border-b pb-2 text-gray-700"
            >
              <span>{b.branch}</span>
              <span>{b.students}</span>
            </div>

          ))}

        </div>

      </div>

      {/* SUBJECT PERFORMANCE */}
      <div className="bg-white rounded-xl shadow p-6 mb-10">

        <h2 className="text-xl font-bold mb-4">
          Subject Performance
        </h2>

        <div className="space-y-3">

          {subjects.map((s, i) => (

            <div
              key={i}
              className="flex justify-between border-b pb-2 text-gray-700"
            >

              <div>

                <p className="font-semibold">
                  {s.subject_code} — {s.subject_name}
                </p>

                <p className="text-sm text-gray-500">
                  {s.faculty}
                </p>

              </div>

              <span className="font-bold">
                {s.percentage}%
              </span>

            </div>

          ))}

        </div>

      </div>

      {/* DEFAULTERS */}
      <div className="bg-white rounded-xl shadow p-6">

        <h2 className="text-xl font-bold mb-4 text-red-600">
          Defaulters
        </h2>

        <table className="w-full">

          <thead>
            <tr className="text-left border-b">
              <th className="py-2">BT ID</th>
              <th>Name</th>
              <th>Attendance</th>
            </tr>
          </thead>

          <tbody>

            {defaulters.map((d, i) => (

              <tr key={i} className="border-b">

                <td className="py-2 font-medium">
                  {d.bt_id}
                </td>

                <td>{d.name}</td>

                <td className="text-red-500 font-semibold">
                  {d.percentage}%
                </td>

              </tr>

            ))}

          </tbody>

        </table>

      </div>

    </div>
  );
}