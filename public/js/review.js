function escapeHtml(text) {
    const div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.reviewForm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const productId = this.getAttribute('data-product');
            const textarea = this.querySelector('textarea[name="comment"]');
            const comment = textarea.value.trim();

            if (comment === '') {
                alert('Review cannot be empty');
                return;
            }

            if (comment.length > 500) {
                alert('Review must be less than 500 characters');
                return;
            }

            fetch('/webtech/Online-Computer-Shop/controllers/ReviewController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=add&product_id=' + encodeURIComponent(productId) +
                      '&comment=' + encodeURIComponent(comment)
            })
            .then(function (response) {
                return response.text();
            })
            .then(function (text) {
                console.log(text);

                let data;

                try {
                    data = JSON.parse(text);
                } catch (error) {
                    alert('Controller returned PHP error. Check Console.');
                    return;
                }

                if (data.success) {
                    const review = data.review;
                    const reviewList = document.getElementById('reviews-list-' + productId);

                    if (!reviewList) {
                        alert('Review list area not found.');
                        return;
                    }

                    const noReviewMessage = reviewList.querySelector('.no-review-message');
                    if (noReviewMessage) {
                        noReviewMessage.remove();
                    }

                    const reviewHtml = `
                        <div id="review-${review.id}" style="border:1px solid #ccc; padding:8px; margin-bottom:8px;">
                            <p>
                                <strong>${escapeHtml(review.reviewer_name)}</strong>
                                <em>(${escapeHtml(review.created_at)})</em>
                            </p>

                            <p id="review-text-${review.id}">
                                ${escapeHtml(review.comment)}
                            </p>

                            <button type="button" onclick="showEditBox(${review.id})">Edit</button>
                            <button type="button" onclick="deleteReview(${review.id})">Delete</button>

                            <div id="edit-box-${review.id}" style="display:none; margin-top:8px;">
                                <textarea id="edit-comment-${review.id}" maxlength="500" style="width:100%; height:60px;">${escapeHtml(review.comment)}</textarea>
                                <br>
                                <button type="button" onclick="updateReview(${review.id})">Update</button>
                                <button type="button" onclick="hideEditBox(${review.id})">Cancel</button>
                            </div>
                        </div>
                    `;

                    reviewList.insertAdjacentHTML('afterbegin', reviewHtml);
                    textarea.value = '';

                    alert('Review added successfully');
                } else {
                    alert(data.error || 'Failed to add review');
                }
            })
            .catch(function (error) {
                console.log(error);
                alert('JS fetch error. Open Inspect > Console to see details.');
            });
        });
    });
});

function showEditBox(reviewId) {
    document.getElementById('edit-box-' + reviewId).style.display = 'block';
}

function hideEditBox(reviewId) {
    document.getElementById('edit-box-' + reviewId).style.display = 'none';
}

function updateReview(reviewId) {
    const textarea = document.getElementById('edit-comment-' + reviewId);
    const comment = textarea.value.trim();

    if (comment === '') {
        alert('Review cannot be empty');
        return;
    }

    if (comment.length > 500) {
        alert('Review must be less than 500 characters');
        return;
    }

    fetch('/webtech/Online-Computer-Shop/controllers/ReviewController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=update&review_id=' + encodeURIComponent(reviewId) +
              '&comment=' + encodeURIComponent(comment)
    })
    .then(function (response) {
        return response.text();
    })
    .then(function (text) {
        console.log(text);

        let data;

        try {
            data = JSON.parse(text);
        } catch (error) {
            alert('Controller returned PHP error. Check Console.');
            return;
        }

        if (data.success) {
            document.getElementById('review-text-' + reviewId).innerText = data.comment;
            hideEditBox(reviewId);
            alert('Review updated successfully');
        } else {
            alert(data.error || 'Failed to update review');
        }
    })
    .catch(function (error) {
        console.log(error);
        alert('JS fetch error. Open Inspect > Console to see details.');
    });
}

function deleteReview(reviewId) {
    if (!confirm('Delete this review?')) {
        return;
    }

    fetch('/webtech/Online-Computer-Shop/controllers/ReviewController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=delete&review_id=' + encodeURIComponent(reviewId)
    })
    .then(function (response) {
        return response.text();
    })
    .then(function (text) {
        console.log(text);

        let data;

        try {
            data = JSON.parse(text);
        } catch (error) {
            alert('Controller returned PHP error. Check Console.');
            return;
        }

        if (data.success) {
            const reviewBox = document.getElementById('review-' + reviewId);

            if (reviewBox) {
                reviewBox.remove();
            }

            alert('Review deleted successfully');
        } else {
            alert(data.error || 'Failed to delete review');
        }
    })
    .catch(function (error) {
        console.log(error);
        alert('JS fetch error. Open Inspect > Console to see details.');
    });
}