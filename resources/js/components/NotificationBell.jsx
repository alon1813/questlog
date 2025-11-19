import React, { useEffect, useState, useRef } from "react";
import axios from "axios"; // <--- Importamos Axios

export default function NotificationsBell() {
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [showDropdown, setShowDropdown] = useState(false);
    const dropdownRef = useRef(null);

    // Configurar Axios para incluir cookies en cada petición de este componente
    // Esto es vital para que Laravel sepa que estás logueado
    const apiClient = axios.create({
        withCredentials: true, // <--- ¡ESTA ES LA CLAVE! Envía las cookies de sesión
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    });


    const fetchNotifications = async () => {
        try {
            // Usamos apiClient en lugar de fetch.
            // Esto envía automáticamente las cookies y el token CSRF necesarios.
            const response = await apiClient.get('/internal/notifications');

            // Con Axios, los datos ya vienen en response.data, no hace falta .json()
            setNotifications(response.data.notifications);
            setUnreadCount(response.data.unread_count);

        } catch (error) {
            // Axios lanza un error si el status es 401, así que lo capturamos aquí
            if (error.response && error.response.status === 401) {
                console.error("No autenticado. El usuario no ha iniciado sesión.");
            } else {
                console.error("Error cargando notificaciones:", error);
            }
        }
    };
    // 2. Función para marcar como leídas
    const markAllAsRead = async () => {
        try {
            await apiClient.post('/internal/notifications/mark-as-read');
            setUnreadCount(0);
            // Opcional: Actualizar la lista para que se vean leídas visualmente
            fetchNotifications(); 
        } catch (error) {
            console.error("Error marcando como leídas:", error);
        }
    };

    // 3. Efecto de Polling
    useEffect(() => {
        fetchNotifications(); // Carga inicial

        const intervalId = setInterval(() => {
            fetchNotifications();
        }, 10000); // Polling cada 10 segundos

        return () => clearInterval(intervalId);
    }, []);

    // 4. Manejar apertura del dropdown
    const handleToggleDropdown = () => {
        const newState = !showDropdown;
        setShowDropdown(newState);
        
        // Si abrimos el menú y hay notificaciones sin leer, las marcamos
        if (newState && unreadCount > 0) {
            markAllAsRead();
        }
    };

    // Cerrar al hacer clic fuera
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setShowDropdown(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    return (
        <div className="relative" ref={dropdownRef}>
            <button
                onClick={handleToggleDropdown}
                className="relative p-2 rounded-full text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white focus:outline-none transition-colors"
            >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                {unreadCount > 0 && (
                    <span className="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                        {unreadCount}
                    </span>
                )}
            </button>

            {showDropdown && (
                <div className="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden z-50 ring-1 ring-black ring-opacity-5 border border-gray-200 dark:border-gray-700">
                    <div className="py-2">
                        <div className="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            Notificaciones
                        </div>
                        <div className="max-h-64 overflow-y-auto">
                            {notifications.length > 0 ? (
                                notifications.map((n) => (
                                    <div key={n.id} className={`block px-4 py-3 text-sm border-b dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700 ${!n.read_at ? 'bg-blue-50 dark:bg-gray-900' : ''}`}>
                                        <p className="text-gray-800 dark:text-gray-200">
                                            {/* Ajusta esto según la estructura de tu 'data' */}
                                            {n.data && n.data.message ? n.data.message : "Nueva notificación"}
                                        </p>
                                        <span className="block text-xs text-gray-500 mt-1">{n.created_at}</span>
                                    </div>
                                ))
                            ) : (
                                <div className="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No tienes notificaciones nuevas.
                                </div>
                            )}
                        </div>
                        {notifications.length > 0 && (
                            <a href="/notificaciones" className="block text-center px-4 py-2 text-sm text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-blue-400">
                                Ver todas
                            </a>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}