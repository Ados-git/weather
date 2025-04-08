document.addEventListener('DOMContentLoaded', function() {
    const podcastId = 1; // This would normally come from your podcast data
    let selectedRating = 0;
    const stars = document.querySelectorAll('.star');
    
    // Initialize star rating system
    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.getAttribute('data-value'));
            updateStarDisplay(selectedRating);
            submitRating(selectedRating);
        });
        
        star.addEventListener('mouseover', function() {
            const hoverValue = parseInt(this.getAttribute('data-value'));
            updateStarDisplay(hoverValue, false);
        });
        
        star.addEventListener('mouseout', function() {
            updateStarDisplay(selectedRating);
        });
    });
    
    function updateStarDisplay(rating, permanent = true) {
        stars.forEach(star => {
            const value = parseInt(star.getAttribute('data-value'));
            if (value <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
        
        if (permanent) {
            document.getElementById('rating-feedback').textContent = 
                rating > 0 ? `You rated this ${rating} star${rating > 1 ? 's' : ''}` : '';
        }
    }
    
    function submitRating(rating) {
        fetch('comments.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=add_rating&podcast_id=${podcastId}&rating_value=${rating}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                loadAverageRating();
            }
        });
    }
    
    function loadAverageRating() {
        fetch(`comments.php?get_rating=1&podcast_id=${podcastId}`)
        .then(response => response.json())
        .then(data => {
            if (data.avg_rating) {
                const avg = parseFloat(data.avg_rating).toFixed(1);
                document.getElementById('average-rating').innerHTML = 
                    `Average Rating: <strong>${avg}</strong> (${data.total_ratings} ratings)`;
            }
        });
    }
    
    // Comment form submission
    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const commentText = document.getElementById('comment-text').value.trim();
        
        if (username && email && commentText) {
            const formData = new FormData();
            formData.append('action', 'add_comment');
            formData.append('podcast_id', podcastId);
            formData.append('username', username);
            formData.append('email', email);
            formData.append('comment_text', commentText);
            
            fetch('comments.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('comment-text').value = '';
                    loadComments();
                    // Create or update user in database
                    updateUser(username, email);
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    });
    
    function updateUser(username, email) {
        const formData = new FormData();
        formData.append('action', 'update_user');
        formData.append('username', username);
        formData.append('email', email);
        
        fetch('comments.php', {
            method: 'POST',
            body: formData
        });
    }
    
    function loadComments() {
        fetch(`comments.php?get_comments=1&podcast_id=${podcastId}`)
        .then(response => response.json())
        .then(comments => {
            const container = document.getElementById('comments-container');
            container.innerHTML = '';
            
            if (comments.length === 0) {
                container.innerHTML = '<p style="color: #ccc; text-align: center;">No comments yet. Be the first to comment!</p>';
                return;
            }
            
            comments.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment';
                
                const headerDiv = document.createElement('div');
                headerDiv.className = 'comment-header';
                
                const userSpan = document.createElement('span');
                userSpan.className = 'comment-user';
                userSpan.textContent = comment.username;
                
                const dateSpan = document.createElement('span');
                dateSpan.className = 'comment-date';
                dateSpan.textContent = new Date(comment.created_at).toLocaleString();
                
                headerDiv.appendChild(userSpan);
                headerDiv.appendChild(dateSpan);
                
                const textDiv = document.createElement('div');
                textDiv.className = 'comment-text';
                textDiv.textContent = comment.comment_text;
                
                commentDiv.appendChild(headerDiv);
                commentDiv.appendChild(textDiv);
                
                container.appendChild(commentDiv);
            });
        });
    }
    
    // Load initial data
    loadAverageRating();
    loadComments();
    
    // Refresh comments every 30 seconds
    setInterval(loadComments, 30000);
});