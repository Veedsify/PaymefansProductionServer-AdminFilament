import React, { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import ChatComponent from "./comps/chat_component";

const App = () => {
  return (
    <div>
      <div>
        <h1>Chat Component</h1>
        <p>This is the chat component!</p>
      </div>
    </div>
  );
};

// Use createRoot for React 18+ (necessary for strict mode and new rendering flow)
const root = createRoot(document.getElementById("root"));
root.render(
  <StrictMode>
  <App />
  </StrictMode>
);
