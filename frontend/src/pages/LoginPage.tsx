import { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function LoginPage() {
  const [btId, setBtId] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const navigate = useNavigate();

  const handleLogin = async () => {
    setError("");

    if (!btId.trim() || !password.trim()) {
      setError("Enter correct ID and Password");
      return;
    }

    try {
      setLoading(true);

      const id = btId.toUpperCase();

      const res = await fetch(
        `http://127.0.0.1:8000/api/student/${id}`
      );

      if (!res.ok) {
        setError("Enter correct ID and Password");
        return;
      }

      // OPTIONAL: If later password validation added → check here

      navigate(`/dashboard/${id}`);
    } catch {
      setError("Server error. Try again.");
    } finally {
      setLoading(false);
    }
  };

  // ENTER KEY SUPPORT
  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === "Enter") handleLogin();
  };

  return (
    <div className="min-h-screen bg-gray-100 flex items-center justify-center px-4 font-roboto">
      
      <div className="bg-white shadow-xl rounded-2xl w-full max-w-md p-10">

        {/* LOGO */}
        <div className="flex justify-center mb-6">
          <img
            src="/logo.png"
            alt="College Logo"
            className="w-170px h-170px object-contain"
          />
        </div>

        {/* TITLE */}
        <h1 className="text-center text-2xl font-bold text-gray-900 mt-2 mb-4">
          Student Login
        </h1>

        {/* INPUTS */}
        <div className="space-y-5">

          <div>
            <label className="block text-sm font-semibold text-gray-600 mb-1">
              Student ID
            </label>
            <input
              value={btId}
              onChange={(e) => setBtId(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="Student ID (e.g., BT240034CS)"
              className="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-gray-600 mb-1">
              Password
            </label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="Password (e.g., 123456)"
              className="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          {/* ERROR MESSAGE */}
          {error && (
            <p className="text-red-500 text-sm font-medium">
              {error}
            </p>
          )}

          {/* LOGIN BUTTON */}
          <button
            onClick={handleLogin}
            disabled={loading}
            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-60"
          >
            {loading ? "Checking..." : "LOGIN"}
          </button>

        </div>
      </div>
    </div>
  );
}
