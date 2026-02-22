import { useState, ChangeEvent } from "react";
import { api } from "../api/client";

const AdminDashboard: React.FC = () => {
  const [files, setFiles] = useState<FileList | null>(null);
  const [message, setMessage] = useState<string>("");

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
  setFiles(e.target.files);
};

  const handleUpload = async () => {
  if (!files) return;

  const formData = new FormData();

  Array.from(files).forEach((file) => {
    formData.append("files[]", file);
  });

const response = await api.post(
    "/admin/upload-csv",
    formData,
    {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }
  );

  setMessage(JSON.stringify(response.data.results, null, 2));
};

  return (
  <div className="min-h-screen bg-gray-100 p-10">
    <div className="max-w-2xl mx-auto bg-white shadow-xl rounded-2xl p-8">

      <h2 className="text-2xl font-bold mb-6 text-gray-800">
        Admin CSV Upload
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
        className="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition"
      >
        Upload CSV Files
      </button>

      <pre className="mt-6 bg-gray-50 p-4 rounded text-sm overflow-auto">
        {message}
      </pre>

    </div>
  </div>
);
};

export default AdminDashboard;