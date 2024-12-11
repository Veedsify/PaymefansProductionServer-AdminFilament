const socket = io(server);
let activeUsers = [];
let joinedRooms = new Set(); // Set to keep track of conversation_ids for which JoinRoomFunc has been called

// Function to handle conversations rendering
const handleConversations = ({ conversations }) => {
  // Clear the current list to avoid duplication
  $("#conversations_list").empty();

  conversations.forEach((data) => {
    const date = (() => {
      try {
        return new Intl.DateTimeFormat("en-NG", {
          hour: "2-digit",
          minute: "2-digit",
          hour12: true,
          day: "numeric",
          month: "short",
        }).format(new Date(data.lastMessage.created_at));
      } catch (error) {
        return "Invalid date";
      }
    })();

    // Call JoinRoomFunc only if it hasn't been called for this conversation_id
    if (!joinedRooms.has(data.conversation_id)) {
      JoinRoomFunc(data.conversation_id);
      joinedRooms.add(data.conversation_id); // Mark this conversation_id as already joined
    }

    // Check if the conversation is unread
    const isUnread =
      !data.lastMessage.seen && data.lastMessage.sender_id !== userId;

    // Render the conversation item
    $("#conversations_list").append(`
      <div 
        data-conversation='${data.conversation_id}'
        class="flex items-center p-4 ${
          isUnread ? "bg-pink-50" : "bg-white"
        } rounded-lg shadow-sm hover:bg-gray-50 cursor-pointer transition conversation-single">
        
        <!-- Profile Image and Status -->
        <div class="relative flex-shrink-0">
          <img src="${data.conversation.profile_image}" alt="User Avatar"
               class="w-12 h-12 rounded-full border-2 border-pink-500">
          <span data-username="${data.conversation.username}" 
                class="absolute bottom-0 right-0 w-3 h-3 border-2 bg-gray-200 border-white rounded-full"></span>
        </div>

        <!-- Conversation Info -->
        <div class="ml-4 flex-grow">
          <div class="flex justify-between items-center">
            <h4 class="text-sm font-semibold text-gray-800">${
              data.conversation.name
            }</h4>
            <span class="text-xs text-gray-500">${date}</span>
          </div>
          <p class="text-sm text-gray-600 truncate">${
            data.lastMessage.message
          }</p>
        </div>
      </div>
    `);
  });
  checkUserAndUpdateStatus(); // Update user statuses after rendering conversations
};

// Function to check and update active user status
const checkUserAndUpdateStatus = () => {
  const users = document.querySelectorAll("[data-username]");

  // Remove the active status for all users initially
  users.forEach((user) =>
    user.classList.replace("bg-green-500", "bg-gray-200")
  );

  // If there are active users, update their status
  if (activeUsers.length) {
    users.forEach((user) => {
      activeUsers.forEach((activeUser) => {
        if (user.getAttribute("data-username") === activeUser.username) {
          user.classList.replace("bg-gray-200", "bg-green-500");
        }
      });
    });
  }
};

// Socket connection and events
socket.on("connect", () => {
  console.log("Connected to socket");
});

socket.on("disconnect", () => {
  console.log("Disconnected from socket");
});

// Emit user-connected event
socket.emit("user-connected", {
  username: "@paymefans",
  userId: userId,
});

// Active users update
socket.on("active_users", (data) => {
  // Only update active users if there are changes
  if (
    data.length === activeUsers.length &&
    data.every((user) =>
      activeUsers.some((activeUser) => activeUser.username === user.username)
    )
  ) {
    return;
  }

  activeUsers = data; // Update active users list
  checkUserAndUpdateStatus(); // Update active status UI
});

// Conversations update
socket.on("conversations", (data) => {
  handleConversations(data); // Render the conversations
});


// Join a room (used by handleConversations)
const JoinRoomFunc = (roomId) => {
  socket.emit("join", roomId); // Emit the join event for the specified roomId
};

// Jquery Functions
$(document).on("click", ".conversation-single", function (e) {
  const parent = e.target.closest(".conversation-single");
  const conversationId = parent.getAttribute("data-conversation");
  console.log(conversationId);
});
