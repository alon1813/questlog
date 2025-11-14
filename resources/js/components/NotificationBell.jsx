import { useEffect, useState } from "react";
import axios from "axios";

export default function NotificationBell() {
    const [open, setOpen] = useState(false);
    const [notifications, setNotifications] = useState([]);
    const [hasUnread, setHasUnread] = useState(false);
    const [loading, setLoading] = useState(true);

    async function loadNotifications() {
        try {
            const res = await axios.get("/api/notifications", { withCredentials: true });
            // Tu API devuelve { unread_count, notifications: [...] }
            setNotifications(res.data.notifications || []);
            setHasUnread((res.data.unread_count || 0) > 0);
        } catch (e) {
            console.error("Error al cargar notificaciones:", e);
        } finally {
            setLoading(false);
        }
    }

    async function markAsRead() {
        try {
            await axios.post("/api/notifications/read", {}, { withCredentials: true });
            await loadNotifications();
        } catch (e) {
            console.error("Error al marcar como leídas:", e);
        }
    }

    useEffect(() => {
        loadNotifications();
        const interval = setInterval(loadNotifications, 4000); // polling
        return () => clearInterval(interval);
    }, []);

    return (
        <div className="relative">
            <button
                onClick={() => {
                    setOpen(!open);
                    if (!open) markAsRead();
                }}
                className="p-2 rounded-full hover:bg-slate-700"
                aria-label="Notificaciones"
            >
                <i className="fa-regular fa-bell text-xl text-white"></i>
                {hasUnread && <span className="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full" />}
            </button>

            {open && (
                <div className="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 text-black dark:text-white shadow-lg rounded-lg z-50 p-3">
                    <h4 className="font-semibold mb-2">Notificaciones</h4>

                    {loading ? (
                        <p className="text-sm text-gray-500">Cargando...</p>
                    ) : notifications.length === 0 ? (
                        <p className="text-sm text-gray-500">No tienes notificaciones</p>
                    ) : (
                        <ul className="max-h-64 overflow-auto space-y-2">
                            {notifications.map(n => (
                                <li key={n.id} className="border-b pb-2 text-sm">
                                    {/* Ajusta según la estructura real de n.data */}
                                    { (n.data && n.data.message) ? n.data.message : JSON.stringify(n.data) }
                                    <div className="text-xs text-gray-400">{n.created_at ?? ''}</div>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            )}
        </div>
    );
}
