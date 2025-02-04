# CP-Wallet-Connector

1. Set up the build process:

Create a `package.json` file in the root of your plugin directory:

Create a `webpack.config.js` file in the root of your plugin directory:


Implementation steps:

1. Create the directory structure as shown at the beginning of this response.
2. Copy each file's content into its respective file in your plugin directory.
3. Replace `'your_blockfrost_api_key_here'` in `blockfrost-api.php` with your actual Blockfrost API key. (DONE)
4. Replace `'your_stake_pool_id_here'` in `blockfrost-api.php` with CarPool's actual stake pool ID.  (DONE)
5. Add wallet icons (PNG format) for each supported wallet in the `images/` directory.
6. In your plugin directory, run `npm install` to install the necessary dependencies.
7. Run `npm run build` to build the React component.
8. Upload the entire `carpool-wallet-connect` directory to your WordPress site's `wp-content/plugins/` directory.
9. Activate the plugin from the WordPress admin panel.
10. Add the shortcode `[carpool_wallet_connect]` to any page or post where you want the wallet connector to appear.
11. Set up a cron job or use WordPress's built-in cron to periodically run the `update_all_users_delegation_info()` function to keep user delegation info up to date.


Remember to thoroughly test the plugin, especially the integration with the Cardano blockchain and WooCommerce discounts. Also, ensure that your server meets all the necessary requirements, including having the required PHP extensions for making HTTP requests to the Blockfrost API.

This implementation provides a comprehensive solution for connecting Cardano wallets, tracking delegations, applying discounts, and managing user roles based on their stake in the CarPool. You may need to make adjustments based on your specific WordPress setup and any additional features you want to implement.
