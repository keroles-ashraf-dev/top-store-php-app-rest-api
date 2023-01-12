import { log, hideFooter } from "../../common/js/helpers.js"

// ************************** global vars *************************************//

const messagesContainer = document.getElementById("js-messages-list-container");
let socket = null;
let userId = '';
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

  initiateSendingMeta(sendMsgBtn);
  initiateSendBtnClickAndMsgData(sendMsgBtn, sendMsgTxt);
}

function initiateSendingMeta(sendMsgBtn) {

  userId = sendMsgBtn.dataset.user;
  channelId = sendMsgBtn.dataset.channel;
}

function initiateSendBtnClickAndMsgData(sendMsgBtn, sendMsgTxt) {

  sendMsgBtn?.addEventListener("click", (_) => {

    if (sendMsgTxt.value.replaceAll(' ', '').length < 1) {
      alert("Message is empty");
      return;
    }

    let data = {
      'senderId': userId,
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
  const date = data['date'] ?? getCurrentDateTime();

  const myMessage = isMyMessage == true ? 'my-message' : '';

  const html = '<div class="row ' + myMessage + '"><div class="message-card ' + myMessage + '"><span class="message-content">' + message + '</span><span class="message-date">' + date + '</span></div></div>';

  messagesContainer.innerHTML += html;
}

function getCurrentDateTime() {
  const dt = new Date();
  return dt.getHours() + ':' + dt.getMinutes() + ' ' + dt.getDate() + '/' + (dt.getMonth() + 1) + '/' + dt.getFullYear();
}