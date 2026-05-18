<tr>
    <td colspan="3" style="text-align:left; background:#f9f9f9; padding:10px;">
        <h4>Customer Reviews</h4>

        <div id="reviews-list-<?php echo $p['id']; ?>">
            <?php
            $reviews = getReviewsByProduct($p['id']);

            if ($reviews->num_rows > 0) {
                while ($r = $reviews->fetch_assoc()) {
            ?>
                    <div id="review-<?php echo $r['id']; ?>" style="border:1px solid #ccc; padding:8px; margin-bottom:8px;">
                        <p>
                            <strong><?php echo htmlspecialchars($r['reviewer_name']); ?></strong>
                            <em>(<?php echo $r['created_at']; ?>)</em>
                        </p>

                        <p id="review-text-<?php echo $r['id']; ?>">
                            <?php echo htmlspecialchars($r['comment']); ?>
                        </p>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $r['user_id']) { ?>
                            <button type="button" onclick="showEditBox(<?php echo $r['id']; ?>)">Edit</button>
                            <button type="button" onclick="deleteReview(<?php echo $r['id']; ?>)">Delete</button>

                            <div id="edit-box-<?php echo $r['id']; ?>" style="display:none; margin-top:8px;">
                                <textarea id="edit-comment-<?php echo $r['id']; ?>" maxlength="500"><?php echo htmlspecialchars($r['comment']); ?></textarea>
                                <br>
                                <button type="button" onclick="updateReview(<?php echo $r['id']; ?>)">Update</button>
                                <button type="button" onclick="hideEditBox(<?php echo $r['id']; ?>)">Cancel</button>
                            </div>
                        <?php } ?>
                    </div>
            <?php
                }
            } else {
                echo "<p class='no-review-message'>No reviews yet.</p>";
            }
            ?>
        </div>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer') { ?>
            <form class="reviewForm" data-product="<?php echo $p['id']; ?>">
                <textarea 
                    name="comment" 
                    placeholder="Write your review" 
                    required 
                    maxlength="500"
                    style="width:100%; height:60px;"
                ></textarea>
                <br>
                <button type="submit">Submit Review</button>
            </form>
        <?php } else { ?>
            <p>Please login as customer to post a review.</p>
        <?php } ?>
    </td>
</tr>