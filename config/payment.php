<?php

/**
 * Payment gateway package configuration file
 *  (c) Peter Andrew Onuh peteritodo@gmail.com
 */

 return [

    /**
     * PAYSTACK API key
     */
        'apiKey' => getenv('PAYSTACK_PUBLIC_KEY'),

     /**
      * PAYSTACK secret key
      */
        'secretKey' => getenv('PAYSTACK_SECRET_KEY'),

      /**
       * PAYSTACK Payment url
       */
         'baseUrl' => getenv('PAYSTACK_BASE_URL'),

       /**
        * Marchant email address
        */
          'marchantEmail' => getenv('PAYSTACK_MARCHANT_EMAIL'),
 ];