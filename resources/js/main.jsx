import React from "react";
import ReactDOM from "react-dom/client";
import { Toaster } from "react-hot-toast";
import "../css/app.css";

import SearchPage from "./components/SearchPage.jsx";
import NotificationsBell from "./components/NotificationBell.jsx";


document.addEventListener("DOMContentLoaded", () => {
    const rootElement = document.getElementById("react-search");
    const bell = document.getElementById("react-notifications");
    if (bell) {
        const root = ReactDOM.createRoot(bell);
        root.render(<NotificationsBell />);
    }

    if (rootElement) {
        const root = ReactDOM.createRoot(rootElement);
        root.render(
            <React.StrictMode>
                {}
                <Toaster
                    position="top-right"
                    toastOptions={{
                        style: {
                            background: "#1e293b",
                            color: "#fff",
                            borderRadius: "10px",
                            fontSize: "0.9rem",
                        },
                        success: {
                            iconTheme: { primary: "#4f46e5", secondary: "#fff" },
                        },
                        error: {
                            iconTheme: { primary: "#dc2626", secondary: "#fff" },
                        },
                    }}
                />
                <SearchPage />
            </React.StrictMode>
        );
    }
});
