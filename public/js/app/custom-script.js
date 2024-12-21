// const socket = io();
// let activeUsers = [];
// // Functions

// const handleConversations = ({ conversations }) => {
//     console.log(conversations);
//     conversations.forEach((data) => {
//         const date = new Intl.DateTimeFormat('en-NG', {
//             hour: '2-digit',
//             minute: '2-digit',
//             hour12: true,
//             day: 'numeric',
//             month: 'short'
//         }).format(new Date(data.lastMessage.created_at));
        
//         const isUnread =
//             !data.lastMessage.seen && data.lastMessage.sender_id !== userId;
//         $("#conversations_list").append(`
//                 <a href="/chat/${
//                     data.conversation_id
//                 }" class="chat-sidebar-single ${isUnread && "unread"} ${
//             activeUsers.find((u) => u.username == data.conversation.username)
//                 ? "active"
//                 : ""
//         }"
//                 data-username="${data.conversation.username}"
//                 wire:navigate
//                 >
//                     <div class="img">
//                         <img src="${
//                             data.conversation.profile_image
//                         }" alt="image" class="rounded-pill">
//                     </div>
//                     <div class="info">
//                         <h6 class="text-sm mb-1">
//                             ${data.conversation.name}
//                         </h6>
//                         <p class="mb-0 text-xs">
//                             ${data.lastMessage.message}
//                         </p>
//                     </div>
//                     <div class="action text-end">
//                         <p class="mb-0 text-neutral-400 text-xs lh-1">
//                         ${date}
//                         </p>
//                         ${
//                             isUnread
//                                 ? `<span class="active-dot-span"></span>`
//                                 : ""
//                         }
//                     </div>
//                 </a>
//             `);
//     });
// };

// const checkUserAndUpdateStatus = () => {
//     const users = document.querySelectorAll("[data-username]");
//     if (!activeUsers.length) {
//         users.forEach((user) => user.classList.remove("active"));
//         return;
//     }

//     users.forEach((user) => {
//         activeUsers.forEach((activeUser) => {
//             if (user.getAttribute("data-username") === activeUser.username) {
//                 user.classList.add("active");
//             }
//         });
//     });
//     return;
// };

// socket.on("connect", () => {
//     console.log("Connected to socket");
// });

// socket.on("disconnect", () => {
//     console.log("Disconnected from socket");
// });

// socket.emit("user-connected", {
//     username: "@paymefans",
//     userId: "0fz13slnz4w8",
// });

// socket.on("active_users", (data) => {
//     if (
//         data.length === activeUsers.length &&
//         data.every((user) =>
//             activeUsers.some(
//                 (activeUser) => activeUser.username === user.username
//             )
//         )
//     ) {
//         return;
//     }
//     activeUsers = data;
//     checkUserAndUpdateStatus();
// });

// socket.on("conversations", (data) => {
//     console.log(data);
//     handleConversations(data);
// });
