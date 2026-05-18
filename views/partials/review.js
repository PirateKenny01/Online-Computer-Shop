document.querySelectorAll('.reviewForm').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const productId = this.getAttribute('data-product');
        const comment = this.querySelector('textarea[name="comment"]').value.trim();

        if (comment === '') {
            alert('Review cannot be empty');
            return;
        }

        if (comment.length > 500) {
            alert('Review must be less than 500 characters');
            return;
        }

        fetch('../controllers/ReviewController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=add&product_id=' + encodeURIComponent(productId) + '&comment=' + encodeURIComponent(comment)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review added successfully');
                location.reload();
            } else {
                alert(data.error || 'Failed to add review');
            }
        });
    });
});

document.querySelectorAll('.deleteReviewBtn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (!confirm('Delete this review?')) {
            return;
        }

        const reviewId = this.getAttribute('data-id');

        fetch('../controllers/ReviewController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=delete&review_id=' + encodeURIComponent(reviewId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review deleted successfully');
                location.reload();
            } else {
                alert(data.error || 'Failed to delete review');
            }
        });
    });
});