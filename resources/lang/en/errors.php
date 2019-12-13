<?php

return array(
  'http' => array(
    404 => array(
      'title' => 'This page does not exist',
      'message' => 'This page could not be found, or does not exist. (Error code: 404)',
    ),
    500 => array(
      'title' => 'Oops...',
      'message' => 'An Internal server error occured. (Error code: 500)',
    ),
    503 => array(
      'title' => 'Maintenance',
      'message' => 'This website is currently undergoing maintenance, come back later.',
    ),
  ),
  'returnhome' => 'Back to Dashboard',
  'returnoverview' => 'Back to Overview',
    'activity-no-internship' => 'You cannot register activities without an active internship.',
    'no-activity-found' => 'Unable to find any activities',
  'internship-no-permission' => 'This internship does not exist, or you have no access to see it.',
  'feedback-permission' => 'You do not have permission to send this feedback.',
  'activity-in-chain' => 'Another activity refers to this activity, thus it cannot be deleted. Remove the chain first on the activity\'s edit page and then try again.',
);
