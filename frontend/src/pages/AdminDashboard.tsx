import { useState, ChangeEvent } from "react";
import { api } from "../api/client";

const AdminDashboard: React.FC = () => {
  const [file, setFile] = useState<File | null>(null);
  const [message, setMessage] = useState<string>("");

  const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      setFile(e.target.files[0]);
    }
  };

  const handleUpload = async () => {
  console.log("Upload button clicked");

  if (!file) {
    setMessage("Please select a file.");
    return;
  }

  const formData = new FormData();
  formData.append("file", file);

  try {
    const response = await api.post("/admin/upload-csv", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    setMessage(response.data.message);
  } catch (error: any) {
    setMessage(
      error.response?.data?.message || "Upload failed."
    );
  }
};

  return (
    <div style={{ padding: "20px" }}>
      <h2>Admin CSV Upload</h2>

      <input type="file" accept=".csv" onChange={handleFileChange} />
      <br /><br />

      <button onClick={handleUpload}>Upload CSV</button>

      <p>{message}</p>
    </div>
  );
};

export default AdminDashboard;