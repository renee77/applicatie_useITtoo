<?php

return [
  // Homepage
  'header' => [
    'aria_login' => 'Log in to your account',
    'login' => 'Login',
    'logged_in' => 'You are logged in as ',
    'logout' => 'Log out',
    'alt_logo' => 'Logo of useITtoo',
    'alt_shopping_cart' => 'shopping cart logo',
    'search_value' => 'Search...',
    'search' => 'Search'
  ],

  'footer' => [
    'home' => 'Home',
    'admin_portal' => 'Admin Portal',
    'webshop' => 'Web Store',
    'newsletter' => 'Newsletter',
    'newsletter_subscribe' => 'Subscribe to our newsletter: ',
    'newsletter_email' => 'Email address for newsletter',
    'subscribe' => 'Subscribe',
    'address_header' => 'Address and Contact',
    'contact' => 'Contact us now',
    'rights' => '© 2025, useITall. All rights reserved.'
  ],

  'homepage' => [
    'title' => 'Welcome',
    'header' => 'Welcome to useITtoo',
    'desc' => 'First-year project by Lonneke van Oers and Eva Bouwman',
  ],

  // Webshop pages
  'webshop' => [
    'title' => 'Web Store',
    'sale' => 'Sale',
    'sale_dummy' => 'Sale dummy',
    'our_products' => 'Our Products',
    'vegetables' => 'Vegetables',
    'fruit' => 'Fruit',
    'longer_shelf_life' => 'Longer Shelf Life'
  ],

  'product' => [
    'buy_now' => 'Buy now',
    'description' => 'Description',
    'origin' => 'Origin'
  ],

  'login' => [
    'login' => 'Log in',
    'username' => 'Username',
    'password' => 'Password'
  ],

  'contact' => [
      'contact_form' => 'Contact form',
      'first_name' => 'First name',
      'last_name' => 'Last name',
      'email' => 'Email',
      'phone_num' => 'Phone number',
      'opt' => '(optional)',
      'message' => 'Message',
      'message_ph' => 'Leave your message here....',
      'send' => 'Send'
  ],

  // Admin pages
  'admin_header' => [
    'home' => 'Home',
    'products' => 'Products',
    'search_terms' => 'Search Terms',
    'upload' => 'Upload',
    'reports' => 'Reports',
    'logout' => 'Log out'
  ],

  'admin_homepage' => [
    'welcome' => 'Welcome, ',
    'quick_product' => 'Quickly create a new product',
    'quick_csv' => 'Quickly upload a CSV file',
    'quick_search_terms' => 'Go to search terms'
  ],

  'admin_product_overview' => [
    'page_title' => 'Products',
    'title' => 'All Products',
    'new_product' => 'Create New Product',
    'type_name' => 'Type the product name.',
    'search' => 'Search',
    'name' => 'Name',
    'price' => 'Price',
    'supplier' => 'Supplier',
    'weight' => 'Weight',
    'description' => 'Description',
    'edit_label' => 'Edit',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'delete_confirm' => 'Are you sure you want to delete this product?: '
  ],

  'admin_product' => [
    'title_new' => 'Create new product',
    'title_edit' => 'Edit product',
    'name' => 'Name:',
    'price' => 'Price:',
    'supplier' => 'Supplier:',
    'stock' => 'Stock:',
    'weight' => 'Weight:',
    'unit' => 'Unit:',
    'image_name' => 'Image name:',
    'description' => 'Description:',
    'save' => 'Save',
    'cancel' => 'Cancel'
  ],

  'upload' => [
    'upload_csv' => 'Upload CSV',
    'upload_image' => 'Upload Image'
  ],

  'upload_csv' => [
    'header_title' => 'Upload',
    'title' => 'Upload your CSV file here',
    'important' => 'Important information!',
    'text' => 'In order to process the CSV file correctly, the content
    in the file must be submitted in the following format:',
    'warning_conditions' => 'If it is not uploaded this way, the row will not be processed!',
    'units' => 'The following units can be used:',
    'upload' => 'Upload',
    'download_text' => 'If you wish, you can download a CSV template below with the correct columns!',
    'download_link' => 'Download CSV Template'
  ],

  'upload_img' => [
    'title' => 'Upload image',
    'important' => 'Upload an image for your products here. Please keep the following points in mind:',
    'img_name' => 'The name your image has when you upload it is the name under which it will be registered.
    Make sure the file has a clear name.',
    'img_types' => 'Images can only be of the types .png, .jpg, .jpeg.',
    'img' => 'Image:',
    'upload' => 'Upload'
  ],

  'searches' => [
    'page_title' => 'Search Terms',
    'title' => 'Search Terms Overview',
    'empty_searches' => 'No search terms have been received yet.',
    'search' => 'Search term',
    'amount' => 'Number of searches',
    'delete' => 'Delete search term',
    'conf' => 'Are you sure you want to delete the following search term?: ',
    'delete_btn' => 'Delete'
  ],

  'notifs' => [
    'no_products' => 'No products found for: ',
    'no_search' => 'Enter a search term to search.',
    'product_made' => 'The following product has been successfully created: ',
    'product_updated' => 'Product successfully updated!',
    'product_deleted' => 'Product successfully deleted!',
    'many_logins' => 'Too many failed attempts. Please try again later.',
    'no_empty' => 'Please enter your username and password',
    'wrong_login' => 'Invalid username or password.',
    'inactive_account' => 'This account is no longer active.',
    'invalid' => 'Invalid username or password',
    'invalid_role' => 'Your account does not have a valid role. Please contact support.',
    'product_not_found' => 'Product not found',
    'products_imported' => ' product(s) successfully imported.',
    'image_imported' => 'Image successfully uploaded',
    'valid_search' => 'Please enter a valid search term',
    'no_products_found' => 'No products found',
    'search_deleted' => 'Search term successfully deleted!',
    'message_sent' => 'Your message has been sent. We will contact you as soon as possible.',
    'message_wrong' => 'Something went wrong while sending. Please try again later.',
    'len_fnshort' => 'First name must be at least 2 characters long.',
    'len_lnshort' => 'Last name must be at least 2 characters long.',
    'invalid_mail' => 'Please enter a valid email address.',
    'invalid_phone' => 'Invalid phone number, please enter a number with 10 digits.',
    'message_short' => 'Message is too short, please enter at least 4 characters.',
    'message_long' => 'A message can have a maximum of 1000 characters. Your message is too long and has this many characters: '
  ]
];
