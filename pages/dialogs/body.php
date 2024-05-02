<?php
use DatabaseClass\Database as Database;

include "Database.php";
include "config.php";

if (!isset($_SESSION["login"])) {
    header("Location: /auth");
    die;
}

$db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);

?>

<link rel="stylesheet" href="/assets/css/dialogs.css">
<div class="container-fluid h-100">
    <div class="row h-100">
        <div class="d-flex d-sm-flex flex-column p-3 text-bg-dark col-sm-3 col-12 h-100" id="sidebar">
            <div class="d-flex justify-content-between">
                <a href="/dialogs"
                   class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-4">Dialogs</span>
                </a>
                <button type="button" class="btn-close btn-close-white d-sm-none d-block"
                        onclick="hideSidebar();"></button>
            </div>
            <hr>
            <ul class="nav d-block nav-pills mb-auto overflow-y-scroll" id="dialogsContainer">
                <li class="nav-item w-100" id="dialog-id-2">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none nav-link active">
                        <div class="avatar-container">
                            <img src="/assets/images/no-avatar.png" alt="User avatar" width="32" height="32"
                                 class="rounded-circle me-2 user-avatar">

                            <span class="badge rounded-pill bg-danger unread-count d-none">0</span>

                            <span class="badge rounded-pill status-indicator d-none">.</span>
                        </div>
                        <div class="w-100 text-truncate">
                            <p class="text-truncate m-0 user-login" title="Test"><b>Test</b></p>
                            <p class="m-0 text-truncate message-text-preview">Message</p>
                        </div>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo(isset($_SESSION["avatar"]) ? $_SESSION["login"] : "/assets/images/no-avatar.png") ?>"
                         alt="User avatar" width="32" height="32" class="rounded-circle me-2">
                    <strong><?php echo $_SESSION["login"] ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newDialogModal">New
                            dialog</a></li>
                    <li><a class="dropdown-item" href="/settings">Settings</a></li>
                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>

        <div class="vr px-0 d-none d-sm-block"></div>

        <div class="col text-bg-dark d-none d-sm-block" id="content">
            <div class="d-flex justify-content-center align-items-center h-100 d-none" id="selDialogContainer">
                <a class="choose-dialog-label text-decoration-none" style="color: white;" onclick="showSidebar();"><-
                    SELECT OR CREATE DIALOG</a>
            </div>
            <div class="d-flex flex-column h-100" id="actualDialogContainer">
                <div class="d-flex gap-1 col-12 mt-3 text-bg-title px-2 py-2">
                    <button type="button" class="btn btn-outline-light d-sm-none d-block p-0 border-0"
                            onclick="showSidebar();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                             class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </button>
                    <p class="h5 mt-2" id="dialogTitle">Test Title</p>
                </div>
                <hr class="w-100 my-1">
                <div class="d-flex flex-grow-1" style="height: 0">
                    <ul class="d-block overflow-y-scroll list-unstyled w-100" id="messagesContainer">
                        <?php foreach($db->getUserMessages($_SESSION["login"]) as $message) { ?>
                            <li class="message my-message">
                                <div>
                                    <span class="message-time">[<?php echo $message["time"] ?>]</span>
                                    <span class="message-text"><?php echo $message["text"] ?></span>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="d-flex gap-1 col-12 mb-3">
                    <div class="d-flex gap-1 col-12 mb-3">
                        <input id="messageInput" type="text" class="form-control" placeholder="Message text..." required
                               maxlength="2048" name="text">
                        <button type="submit" class="btn btn-primary" onclick="sendMessage();">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="newDialogModal" tabindex="-1" aria-labelledby="newDialogodalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newDialogAppModalLabel">New dialog</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="newDialogAlertContainer"></div>
                <div class="form-group">
                    <label for="adddial_userName">Username</label>
                    <input type="text" class="form-control" id="adddial_userName"
                           placeholder="Enter another user's name" required maxlength="32">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="/*newDialog();*/">Create</button>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dialogs.js"></script>