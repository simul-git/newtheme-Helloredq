<?php
/* Template Name: RedQ Contact */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <h1>Contact Us</h1>

        <form id="redq-contact-form">
            <p>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </p>
            <p>
                <button type="submit" id="submit-btn">Submit</button>
            </p>
        </form>

        <p id="form-message" style="display:none;">Form sent successfully!</p>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
