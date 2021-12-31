<?php

/**
 * Monnify laravel payment gateway configuration file
 *  (c) Peter Andrew Onuh peteritodo@gmail.com
 */

 return [

    /**
     * MONNIFY API key
     */
        'apiKey' => getenv('MONNIFY_API_KEY'),

     /**
      * MONNIFY secret key
      */
        'secretKey' => getenv('MONNIFY_SECRET_KEY'),

     /**
      * MONNIFY Wallet ID
      */
        'walletID' => getenv('MONNIFY_WALLET'),

     /**
      * MONNIFY Contract code
      */
        'contractCode' => getenv('MONNIFY_CONTRACT_CODE'),

      /**
       * MONNIFY Payment url
       */
         'baseUrl' => getenv('MONNIFY_BASE_URL'),

       /**
        * Marchant email address
        */
          'marchantEmail' => getenv('MONNIFY_MARCHANT_EMAIL'),

       /**
        * Auth Token
        */
          'token' => getenv('MONNIFY_TOKEN_KEY'),
 ];