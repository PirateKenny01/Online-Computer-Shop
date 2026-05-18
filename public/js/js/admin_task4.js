// Done by: 22-49926-3
// Task 4: Admin AJAX delete for reviews and customers

function deleteReviewByAdmin(reviewId) 
{
    if (!confirm('Are you sure you want to delete this review?')) 
    {
        return;
    }

    fetch('/webtech/Online-Computer-Shop/controllers/AdminTask4Controller.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=delete_review&review_id=' + encodeURIComponent(reviewId)
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(text) {
        console.log(text);

        var data;

        try {
            data = JSON.parse(text);
        } catch (e) {
            alert('Controller returned PHP error. Open Console to see details.');
            return;
        }

        var message = document.getElementById('reviewMessage');

        if (data.success) 
        {
            var row = document.getElementById('review-row-' + reviewId);

            if (row) 
            {
                row.remove();
            }

            if (message) 
            {
                message.style.color = 'green';
                message.innerText = data.message;
            }

            alert(data.message);
        } 
        else 
        {
            if (message) 
            {
                message.style.color = 'red';
                message.innerText = data.error || 'Review delete failed';
            }

            alert(data.error || 'Review delete failed');
        }
    })
    .catch(function(error) {
        console.log(error);
        alert('Something went wrong while deleting review.');
    });
}

function deleteCustomerFromReviewPage(customerId) 
{
    if (!confirm('Are you sure? This will delete this customer with reviews, cart items, orders and order items.')) 
    {
        return;
    }

    fetch('/webtech/Online-Computer-Shop/controllers/AdminTask4Controller.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=delete_customer&customer_id=' + encodeURIComponent(customerId)
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(text) {
        console.log(text);

        var data;

        try {
            data = JSON.parse(text);
        } catch (e) {
            alert('Controller returned PHP error. Open Console to see details.');
            return;
        }

        var message = document.getElementById('reviewMessage');

        if (data.success) 
        {
            var rows = document.querySelectorAll('.customer-review-row-' + customerId);

            rows.forEach(function(row) {
                row.remove();
            });

            if (message) 
            {
                message.style.color = 'green';
                message.innerText = data.message;
            }

            alert(data.message);
        } 
        else 
        {
            if (message) 
            {
                message.style.color = 'red';
                message.innerText = data.error || 'Customer delete failed';
            }

            alert(data.error || 'Customer delete failed');
        }
    })
    .catch(function(error) {
        console.log(error);
        alert('Something went wrong while deleting customer.');
    });
}

function deleteCustomerByAdmin(customerId) 
{
    deleteCustomerFromReviewPage(customerId);
}