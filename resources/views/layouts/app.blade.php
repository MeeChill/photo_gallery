<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dark Gallery') - Pinterest Style</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom Dark Theme -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#0f172a',
                            card: '#1e293b',
                            border: '#334155',
                            text: '#e2e8f0',
                            muted: '#94a3b8',
                            accent: '#8b5cf6',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        /* Chart Container Styles - Light theme on dark background */
.chart-container {
    background: #1e293b;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    border: 1px solid #334155;
}

.chart-title {
    font-size: 16px;
    font-weight: 600;
    color: #e2e8f0;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chart-subtitle {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 400;
}

/* Simple Bar Chart Styles */
.simple-bar {
    height: 200px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 10px 0;
    background: #0f172a;
    border-radius: 8px;
    padding: 15px;
}

.bar-item {
    flex: 1;
    margin: 0 4px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    height: 100%;
}

.bar {
    width: 100%;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px 4px 0 0;
    transition: all 0.3s ease;
    position: relative;
    min-height: 5px;
}

.bar:hover {
    opacity: 0.8;
    transform: translateY(-2px);
}

.bar-label {
    font-size: 10px;
    color: #94a3b8;
    margin-top: 8px;
    text-align: center;
}

.bar-value {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 10px;
    font-weight: 600;
    color: #e2e8f0;
    opacity: 0;
    transition: opacity 0.3s ease;
    background: #1e293b;
    padding: 2px 6px;
    border-radius: 4px;
    white-space: nowrap;
}

.bar-item:hover .bar-value {
    opacity: 1;
}

/* Donut Chart Styles */
.donut-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 200px;
    background: #0f172a;
    border-radius: 8px;
    padding: 20px;
}

.donut-chart {
    position: relative;
    width: 150px;
    height: 150px;
}

.donut-legend {
    flex: 1;
    padding-left: 30px;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    background: #1e293b;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.legend-item:hover {
    background: #334155;
    transform: translateX(4px);
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
    margin-right: 10px;
}

.legend-text {
    font-size: 13px;
    color: #e2e8f0;
    flex: 1;
}

.legend-value {
    font-size: 13px;
    font-weight: 600;
    color: #e2e8f0;
}

/* Update stat cards to match */
.stat-card {
    background: #1e293b;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    border: 1px solid #334155;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    border-color: #475569;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 12px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #e2e8f0;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: #94a3b8;
    font-weight: 500;
}

.stat-change {
    font-size: 12px;
    margin-top: 8px;
    display: flex;
    align-items: center;
}

.stat-change.positive {
    color: #10b981;
}

.stat-change.negative {
    color: #ef4444;
}

/* Table updates */
.chart-container .space-y-3 > div {
    background: #0f172a;
    border: 1px solid #334155;
}

.chart-container .space-y-3 > div:hover {
    background: #1e293b;
    border-color: #475569;
}


        /* Pinterest Masonry Layout */
        .masonry-grid {
            column-count: 5;
            column-gap: 1rem;
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
        }

        @media (max-width: 1280px) {
            .masonry-grid { column-count: 4; }
        }
        @media (max-width: 1024px) {
            .masonry-grid { column-count: 3; }
        }
        @media (max-width: 768px) {
            .masonry-grid { column-count: 2; }
        }
        @media (max-width: 480px) {
            .masonry-grid { column-count: 1; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: #8b5cf6;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }

        /* Hover Effects */
        .photo-card {
            transition: all 0.3s ease;
        }

        .photo-card:hover {
            transform: translateY(-5px);
        }

        .photo-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .photo-card:hover .photo-overlay {
            opacity: 1;
        }

        .action-buttons {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .photo-card:hover .action-buttons {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-dark-bg text-dark-text min-h-screen">
    <!-- Navigation -->
    <nav class="bg-dark-card border-b border-dark-border sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('gallery.index') }}" class="flex items-center space-x-2">
                    <i class="fas fa-images text-2xl text-purple-500"></i>
                    <span class="text-xl font-bold">PinSpace</span>
                </a>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search photos..."
                               class="w-full bg-dark-bg border border-dark-border rounded-full py-2 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-search absolute left-3 top-3 text-dark-muted"></i>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg hover:bg-gray-100 transition text-gray-700">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </a>
                @else
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-cog mr-2"></i>Admin
                        </a>
                    @endif
                        <a href="{{ route('gallery.create') }}" class="px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-plus mr-2"></i>Upload
                        </a>
                            <a href="{{ route('boards.index') }}" class="px-4 py-2 bg-dark-bg rounded-lg hover:bg-dark-border transition">
                                <i class="fas fa-folder mr-2"></i>Boards
                            </a>
                            <a href="{{ route('saved.index') }}" class="px-4 py-2 bg-dark-bg rounded-lg hover:bg-dark-border transition">
                                <i class="fas fa-bookmark mr-2"></i>Saved
                            </a>
                        <div class="relative">
                            <button id="profile-menu-btn" class="flex items-center space-x-2 focus:outline-none">
                                <img src="{{ auth()->user()->avatar ?: asset('images/default-avatar.png') }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-dark-card rounded-lg shadow-lg border border-dark-border">
                                <a href="{{ route('profile.show', auth()->user()) }}" class="block px-4 py-2 hover:bg-dark-bg">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-dark-bg">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <hr class="border-dark-border my-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-dark-bg">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @if (session('success'))
            <div class="bg-green-900 text-green-200 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button class="ml-auto" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif
            <div id="downloadNotification" class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full transition-transform duration-300 flex items-center hidden">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Download started!</span>
            </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-card border-t border-dark-border mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-dark-muted">
            <p>&copy; 2023 PinSpace. All rights reserved.</p>
            <div class="mt-2 flex justify-center space-x-4">
                <a href="#" class="hover:text-purple-400"><i class="fab fa-github"></i></a>
                <a href="#" class="hover:text-purple-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-purple-400"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Setup CSRF token untuk semua AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Profile dropdown menu
    document.getElementById('profile-menu-btn')?.addEventListener('click', function() {
        document.getElementById('profile-menu').classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const profileMenu = document.getElementById('profile-menu');
        const profileMenuBtn = document.getElementById('profile-menu-btn');
        if (profileMenu && !profileMenuBtn.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.classList.add('hidden');
        }
    });

    // Fungsi untuk toggle like - Perbaikan URL
    function toggleLike(photoId) {
        $.post(`/gallery/${photoId}/like`, function(data) {
            const likeBtn = $(`.like-btn-${photoId}`);
            const likeCount = $(`.like-count-${photoId}`);

            // Update ikon
            const icon = likeBtn.find('i');
            if (data.liked) {
                icon.removeClass('text-white').addClass('text-red-500');
                likeBtn.removeClass('bg-dark-bg hover:bg-dark-border').addClass('bg-red-900 text-red-200');
            } else {
                icon.removeClass('text-red-500').addClass('text-white');
                likeBtn.removeClass('bg-red-900 text-red-200').addClass('bg-dark-bg hover:bg-dark-border');
            }

            // Update counter
            likeCount.html(`<i class="fas fa-heart text-red-500 mr-1"></i> ${data.count}`);
        }).fail(function(xhr) {
            if (xhr.status === 401) {
                alert('Please login to like photos');
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }

    // Fungsi untuk toggle save - Perbaikan URL
    function toggleSave(photoId) {
        $.post(`/gallery/${photoId}/save`, function(data) {
            const saveBtn = $(`.save-btn-${photoId}`);
            const saveCount = $(`.save-count-${photoId}`);

            // Update ikon
            const icon = saveBtn.find('i');
            if (data.saved) {
                icon.removeClass('text-white').addClass('text-purple-500');
                saveBtn.removeClass('bg-dark-bg hover:bg-dark-border').addClass('bg-purple-900 text-purple-200');
            } else {
                icon.removeClass('text-purple-500').addClass('text-white');
                saveBtn.removeClass('bg-purple-900 text-purple-200').addClass('bg-dark-bg hover:bg-dark-border');
            }

            // Update counter
            saveCount.html(`<i class="fas fa-bookmark text-purple-500 mr-1"></i> ${data.count}`);
        }).fail(function(xhr) {
            if (xhr.status === 401) {
                alert('Please login to save photos');
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }

    function openShareModal(photoId) {
        const photoUrl = `${window.location.origin}/gallery/${photoId}`;
        document.getElementById('shareLink').value = photoUrl;

        // Set social media share links
        document.getElementById('shareFacebook').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(photoUrl)}`;
        document.getElementById('shareTwitter').href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(photoUrl)}&text=Check out this amazing photo!`;
        document.getElementById('sharePinterest').href = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(photoUrl)}`;
        document.getElementById('shareWhatsApp').href = `https://wa.me/?text=${encodeURIComponent(`Check out this amazing photo: ${photoUrl}`)}`;

        document.getElementById('shareModal').classList.remove('hidden');
    }

    function closeShareModal() {
        document.getElementById('shareModal').classList.add('hidden');
    }

    function copyShareLink() {
        const shareLink = document.getElementById('shareLink');
        shareLink.select();
        document.execCommand('copy');

        // Tampilkan notifikasi
        const button = event.target;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const downloadLinks = document.querySelectorAll('a[href*="/download"]');
        downloadLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                showDownloadNotification();
            });
        });
    });

    function showDownloadNotification() {
        const notification = document.getElementById('downloadNotification');
        notification.classList.remove('hidden');
        notification.classList.remove('translate-y-full');

        setTimeout(() => {
            notification.classList.add('translate-y-full');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 300); // Wait for transition to complete
        }, 3000);
    }

    // Comment functions
function toggleCommentForm() {
    const form = document.getElementById('commentForm');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        document.getElementById('commentInput').focus();
    }
}

function submitComment(event, photoId) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;

    // Disable button and show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

    fetch(`/photos/${photoId}/comments`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            // If response is not OK, try to get the error message
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, text: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Add comment to the list
            const commentsList = document.getElementById('commentsList');
            const emptyMessage = commentsList.querySelector('.text-center');
            if (emptyMessage) {
                emptyMessage.remove();
            }

            const commentHtml = `
                <div class="flex space-x-3 p-4 bg-dark-bg rounded-lg" id="comment-${data.comment.id}">
                    <img src="${data.comment.user.avatar ? '/storage/' + data.comment.user.avatar : '/images/default-avatar.png'}"
                         alt="${data.comment.user.name}"
                         class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-white">${data.comment.user.name}</h4>
                                <p class="text-sm text-gray-400">Baru saja</p>
                            </div>
                            <div class="relative">
                                <button onclick="toggleCommentMenu(${data.comment.id})" class="text-gray-400 hover:text-white">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="commentMenu-${data.comment.id}" class="hidden absolute right-0 mt-2 w-48 bg-dark-card rounded-lg shadow-lg border border-dark-border z-10">
                                    <button onclick="deleteComment(${photoId}, ${data.comment.id})" class="w-full text-left px-4 py-2 hover:bg-dark-bg transition">
                                        <i class="fas fa-trash mr-2 text-red-500"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-300">${data.comment.comment}</p>
                    </div>
                </div>
            `;

            commentsList.insertAdjacentHTML('afterbegin', commentHtml);

            // Update comments count
            const commentsCount = document.querySelector('.text-xl.font-bold.text-white');
            if (commentsCount) {
                commentsCount.textContent = `Komentar (${data.comments_count})`;
            }

            // Reset form
            form.reset();
            toggleCommentForm();

            // Show success message
            showNotification('Komentar berhasil ditambahkan!', 'success');
        } else {
            // Show error message
            showNotification(data.message || 'Gagal menambahkan komentar', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    })
    .finally(() => {
        // Re-enable button and restore original text
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

function deleteComment(photoId, commentId) {
    if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
        return;
    }

    fetch(`/photos/${photoId}/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, text: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove comment from DOM
            const commentElement = document.getElementById(`comment-${commentId}`);
            if (commentElement) {
                commentElement.remove();
            }

            // Update comments count
            const commentsCount = document.querySelector('.text-xl.font-bold.text-white');
            if (commentsCount) {
                commentsCount.textContent = `Komentar (${data.comments_count})`;
            }

            // Check if no comments left
            const commentsList = document.getElementById('commentsList');
            if (data.comments_count === 0 && commentsList.children.length === 0) {
                commentsList.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    </div>
                `;
            }

            showNotification('Komentar berhasil dihapus!', 'success');
        } else {
            showNotification(data.message || 'Gagal menghapus komentar', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    });
}

function toggleCommentMenu(commentId) {
    const menu = document.getElementById(`commentMenu-${commentId}`);
    menu.classList.toggle('hidden');
}

// Close comment menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        document.querySelectorAll('[id^="commentMenu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-600' :
        type === 'error' ? 'bg-red-600' : 'bg-blue-600'
    } text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
    </script>

    @stack('scripts')
</body>
</html>
