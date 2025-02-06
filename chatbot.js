$(document).ready(function () {
    // Function to open the chat form
    function openForm() {
        $("#myForm").show();
    }

    // Function to close the chat form
    function closeForm() {
        $("#myForm").hide();
    }

    // Event listener for the "Chat" button
    $(".open-button").click(openForm);

    // Event listener for the "Close" button
    $(".cancel").click(closeForm);

    // Event listener for form submission
    $('#chat-form').submit(function (event) {
        event.preventDefault(); // Prevent default form submission
        var message = $('textarea[name="input"]').val(); // Get the message from the textarea
        if (message.trim() !== '') { // Check if the message is not empty
            appendUserMessage(message); // Append the user's message to the chat box with label
            sendMessageToChatbot(message); // Send the user's message to the chatbot
        }
        // Clear the textarea after submission
        $('textarea[name="input"]').val('');
    });

    // Function to append user's message to the chat
    function appendUserMessage(message) {
        $('#chat-messages').append('<div class="message-label">You:</div>');
        $('#chat-messages').append('<div class="user-message">' + message + '</div>');
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }

    // Function to send the message to the chatbot via AJAX
    function sendMessageToChatbot(message) {
        // Disable form submission while the AJAX request is in progress
        $('#chat-submit').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: 'chatbot_backend.php', // The URL of the PHP file to handle OpenAI request
            data: { input: message },
            success: function (response) {
                // Append chatbot's response to the chat
                $('#chat-messages').append('<div class="message-label">Chatbot:</div>');
                $('#chat-messages').append('<div class="message">' + response + '</div>');
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#chat-messages').append('<div class="error-message">Error: ' + errorThrown + '</div>');
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            },
            complete: function () {
                // Re-enable form submission after the request is completed
                $('#chat-submit').prop('disabled', false);
            }
        });
    }
});
