const API = "http://localhost:8000";

export async function login(email: string, password: string) {
    // 1) Get CSRF cookie (required by Sanctum)
    await fetch(`${API}/sanctum/csrf-cookie`, {
        credentials: "include",
    });

    // 2) Send login request
    const res = await fetch(`${API}/login`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify({ email, password }),
    });

    return res.json();
}

export async function logout() {
    await fetch(`${API}/logout`, {
        method: "POST",
        credentials: "include",
    });
}

export async function getUser() {
    const res = await fetch(`${API}/user`, {
        credentials: "include",
    });
    return res.json();
}