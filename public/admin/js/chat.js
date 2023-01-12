import { log, hideFooter } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const chatsList = document.getElementById("js-chats-list-container");
const messagesContainer = document.getElementById("js-messages-list-container");

let socket = null;
let adminId = '';
let receiverId = '';
let channelId = '';

// ************************** functions call *************************************//

hideFooter() // hide footer tag
initiateScrollToBottom() // scroll messages list to bottom
initiateData() // initiate send msg btn click and user token and channel id
startChatWebsocket() // connect cht socket service

// ************************** functions deceleration *************************************//

function initiateScrollToBottom() {
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function initiateData() {

  const sendMsgTxt = document.getElementById("js-message-content");
  const sendMsgBtn = document.getElementById("js-send-message-btn");

  initiateSendingMeta(sendMsgBtn, chatsList);
  initiateSendBtnClickAndMsgData(sendMsgBtn, sendMsgTxt);
}

function initiateSendingMeta(sendMsgBtn, chatsList) {

  adminId = sendMsgBtn.dataset.admin;

  const activeChat = chatsList?.querySelector('.active');

  receiverId = activeChat.dataset.receiver;
  channelId = activeChat.dataset.channel;
}

function initiateSendBtnClickAndMsgData(sendMsgBtn, sendMsgTxt) {

  sendMsgBtn?.addEventListener("click", (_) => {

    if (sendMsgTxt.value.replaceAll(' ', '').length < 1) {
      alert("Message is empty");
      return;
    }

    let data = {
      'senderId': adminId,
      'receiverId': receiverId,
      'channelId': channelId,
      'message': sendMsgTxt.value,
    };

    sendMsgTxt.value = '';

    data = JSON.stringify(data)

    addMessageToDocument(JSON.parse(data), true);
    initiateScrollToBottom()
    sendMessage(data);
  })

}

function startChatWebsocket() {

  const wsUri = "ws://localhost:8080/store/websocket/chat/run.php";
  socket = new WebSocket(wsUri);

  socket.onopen = function (ev) { log('socket opened: ' + ev); }
  socket.onerror = function (ev) { log('socket error: ' + ev); };
  socket.onclose = function (ev) { log('socket closed: ' + ev); };
  socket.onmessage = function (ev) {
    const res = JSON.parse(ev.data);
    log(res);
    addMessageToDocument(res)
  };

}

function sendMessage(data) {
  socket.send(data);
}

function addMessageToDocument(data, isMyMessage = false) {

  const message = data['message'];
  const senderId = data['senderId'];
  const date = data['date'] ?? getCurrentDateTime();

  const myMessage = isMyMessage == true ? 'my-message' : '';

  const chats = chatsList?.querySelectorAll('.chat-card');

  let selectedChatCard = null;

  const count = chats.length;

  for (let i = 0; i < count; i++) {

    if (senderId == chats[i].dataset.receiver) {
      selectedChatCard = chats[i];
      break;
    }
  }

  if (selectedChatCard.classList.contains('active')) {

    const html = '<div class="row ' + myMessage + '"><div class="message-card ' + myMessage + '"><span class="message-content">' + message + '</span><span class="message-date">' + date + '</span></div></div>';
    messagesContainer.innerHTML += html;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    return;
  }

  selectedChatCard.classList.add("new-message");

}

function getCurrentDateTime() {
  const dt = new Date();
  return dt.getHours() + ':' + dt.getMinutes() + ' ' + dt.getDate() + '/' + (dt.getMonth() + 1) + '/' + dt.getFullYear();
}