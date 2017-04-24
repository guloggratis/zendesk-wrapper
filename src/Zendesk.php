<?php
/**
 * Zendesk.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  06-04-2017
 */

include("API/Wrapper.php");
include("API/Ticket.php");
include("API/User.php");

include("Http.php");
include("HttpClient.php");

include("Utilities/Auth.php");

include("Resources/ResourceAbstract.php");

include("Resources/Core/Tickets.php");
include("Resources/Core/Users.php");
include("Resources/Core/UserTickets.php");
include("Resources/Core/Attachments.php");