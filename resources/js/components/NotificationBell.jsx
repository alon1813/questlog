import React, { useEffect, useState, useRef } from "react";
import axios from "axios";

export default function NotificationsBell() {
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [showDropdown, setShowDropdown] = useState(false);
    const [loading, setLoading] = useState(true);
    const dropdownRef = useRef(null);
    

    const apiClient = axios.create({
        withCredentials: true,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    });

    const getNotificationLink = (notifications) =>{
        const data = notifications.data;
        switch(notifications.type) {
            case 'NewFollowerNotification':
                return data.follower_id 
                ? `/usuarios/${data.follower_id}` 
                : '/notificaciones';
            
            case 'NewCommentNotification':
                return data.post_id 
                ? `/noticias/${data.post_id}` 
                : '/notificaciones';
            
            case 'NewLikeNotification': 
                return data.liker_username 
                    ? `/usuarios/${data.liker_username}` 
                    : '/notificaciones';

            default:
                return '/notificaciones';
        }
    }

    const initializeCsrf = async () => {
        try {
            await axios.get('/sanctum/csrf-cookie');
        } catch (error) {
            console.error("Error obteniendo CSRF token:", error);
        }
    };

    const fetchNotifications = async () => {
        try {
            setLoading(true);
            const response = await apiClient.get('/internal/notifications');
            setNotifications(response.data.notifications);
            setUnreadCount(response.data.unread_count);
        } catch (error) {
            if (error.response && error.response.status === 401) {
                console.error("No autenticado. El usuario no ha iniciado sesión.");
            } else {
                console.error("Error cargando notificaciones:", error);
            }
        }finally{
            setLoading(false);
        }
    };

    const markAllAsRead = async () => {
        try {
            await apiClient.post('/internal/notifications/mark-as-read');
            setUnreadCount(0);
            fetchNotifications();
        } catch (error) {
            console.error("Error marcando como leídas:", error);
        }
    };

    useEffect(() => {
        const initialize = async () => {
            await initializeCsrf();
            await fetchNotifications();
        };

        initialize();

        
        const intervalId = setInterval(() => {
            fetchNotifications();
        }, 5000);

        return () => clearInterval(intervalId);
    }, []);

    const handleToggleDropdown = () => {
        const newState = !showDropdown;
        setShowDropdown(newState);
        
        if (newState && unreadCount > 0) {
            markAllAsRead();
        }
    };

    
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
                aria-label="Notificaciones"
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
                            {loading ? (
                                <div className="px-4 py-8 flex justify-center">
                                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                                </div>
                            ) :notifications.length > 0 ? (
                                notifications.map((n) => (
                                    <div 
                                        key={n.id} 
                                        href={getNotificationLink(n)}
                                        className={`block px-4 py-3 text-sm border-b dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors ${
                                            !n.read_at ? 'bg-blue-50 dark:bg-gray-900' : ''
                                        }`}
                                    >
                                        
                                        <div className="flex items-start space-x-3">
                                            <div className="flex-shrink-0 mt-1">
                                                {n.type === 'NewFollowerNotification' && (
                                                    <svg className="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                                    </svg>
                                                )}
                                                {n.type === 'NewCommentNotification' && (
                                                    <svg className="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clipRule="evenodd" />
                                                    </svg>
                                                )}
                                                {n.type === 'NewLikeNotification' && (
                                                    <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clipRule="evenodd" />
                                                    </svg>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-gray-800 dark:text-gray-200">
                                                    {n.data?.message || "Nueva notificación"}
                                                </p>
                                                <span className="block text-xs text-gray-500 mt-1">
                                                    {n.created_at}
                                                </span>
                                            </div>
                                            {!n.read_at && (
                                                <span className="flex-shrink-0 inline-block w-2 h-2 bg-blue-500 rounded-full mt-2"></span>
                                            )}
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="px-4 py-8 text-center">
                                    <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        No tienes notificaciones nuevas.
                                    </p>
                                </div>
                            )}
                        </div>
                        {notifications.length > 0 && (
                            <a 
                                href="/notificaciones" 
                                className="block text-center px-4 py-2 text-sm text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-blue-400 border-t dark:border-gray-700"
                            >
                                Ver todas las notificaciones →
                            </a>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}