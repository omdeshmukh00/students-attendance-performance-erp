import { useState } from "react";
import { api } from "../api/client";

const AdminDashboard: React.FC = () => {
  const [files, setFiles] = useState<FileList | null>(null);
  const [message, setMessage] = useState<string>("");

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
  setFiles(e.target.files);
};
  const [loading, setLoading] = useState(false);
  const [progress, setProgress] = useState(0);

  const handleUpload = async () => {
  if (!files) {
    setMessage("Please select files.");
    return;
  }

  const formData = new FormData();

  Array.from(files).forEach((file) => {
    formData.append("files[]", file);
  });

  try {
    setLoading(true);
    setMessage("");

    const response = await api.post(
      "/admin/upload-csv",
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );

    setMessage(
      JSON.stringify(response.data.results, null, 2)
    );
  } catch (error: any) {
    setMessage(
      error.response?.data?.message || "Upload failed."
    );
  } finally {
    setLoading(false);
  }
};

  return (
  <div className="min-h-screen bg-gray-100 p-10">
    <div className="max-w-2xl mx-auto bg-white shadow-xl rounded-2xl p-8">

      <h2 className="text-2xl font-bold mb-6 text-gray-800">
        Admin Attendance Upload
      </h2>

      <input
        type="file"
        multiple
        accept=".csv"
        onChange={handleFileChange}
        className="mb-4"
      />

      <button
        onClick={handleUpload}
        disabled={loading}
        className={`flex items-center justify-center gap-2 px-6 py-2 rounded-lg font-semibold text-white ${
          loading
            ? "bg-gray-400 cursor-not-allowed"
            : "bg-blue-600 hover:bg-blue-700"
        }`}
      >
        {loading && (
          <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
        )}
        {loading ? "Importing..." : "Upload CSV Files"}
      </button>


      {loading && (
        <div className="mt-4 w-full bg-gray-200 rounded-full h-4 overflow-hidden">
          <div
            className="bg-blue-600 h-4 transition-all duration-300"
            style={{ width: `${progress}%` }}
          />
        </div>
      )}

      <pre className="mt-6 bg-gray-50 p-4 rounded text-sm overflow-auto">
        {message}
      </pre>

    </div>
  </div>
);
};

export default AdminDashboard;