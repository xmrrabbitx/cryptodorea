<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;
if( ! defined('WCSF_PLUGIN_FILE') ) {
    define( 'WCSF_PLUGIN_FILE', __FILE__ );
}

if( ! defined('WCSF_PLUGIN_DIR') ) {
    define( 'WCSF_PLUGIN_DIR', __DIR__ );
}

/**
 * load necessary admin files
 */
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/admin/admin.php';
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/checkout/checkout.php';
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/wp/wp.php';
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/modals/userStatusCampaign.php';


// wait until admin panel fully loads
add_action('admin_menu','admin_init');
function admin_init():void
{
    // remove admin footer label
    add_filter( 'admin_footer_text', '__return_empty_string', 11 );
    add_filter( 'update_footer', '__return_empty_string', 11 );

    // core js style
    wp_enqueue_script('DOREA_CORE_STYLE', plugins_url('/cryptodorea/js/style.min.js'));

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_deployfailbreak', 10, 3);
    function add_type_deployfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_DEPLOYFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_fundfailbreak', 10, 3);
    function add_type_fundfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_FUNDFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_payfailbreak', 10, 3);
    function add_type_payfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_PAYFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_campaigncredit', 10, 3);
    function add_type_campaigncredit($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CAMPAIGNCREDIT_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_checkout', 10, 3);
    function add_type_checkout($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CHECKOUT_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_checkout_legacy', 10, 3);
    function add_type_checkout_legacy($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CHECKOUTLEGACY_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_checkoutbeforeproccessed', 10, 3);
    function add_type_checkoutbeforeproccessed($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CHECKOUT_BEFORE_PROCESSED_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_ordered', 10, 3);
    function add_type_ordered($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_ORDERED_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to script
    add_filter('script_loader_tag', 'add_type_userStatusCampaign', 10, 3);
    function add_type_userStatusCampaign($tag, $handle, $src)
    {

        // if not your script, do nothing and return original $tag
        if ('DOREA_USERSTATUSCAMPAIGN_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to script
    add_filter('script_loader_tag', 'add_type_pay', 10, 3);
    function add_type_pay($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_PAY_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    add_filter('script_loader_tag', 'add_type_fund', 10, 3);
    function add_type_fund($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_FUND_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

}

// remove after testting
//add_action('admin_menu','deploy');
/*
function deploy():void
{
print('<script type="module">

let abi = [
	{
		"inputs": [],
		"stateMutability": "nonpayable",
		"type": "constructor"
	},
	{
		"inputs": [],
		"name": "latestPrice",
		"outputs": [
			{
				"internalType": "int256",
				"name": "",
				"type": "int256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_receipent",
				"type": "address"
			},
			{
				"internalType": "string",
				"name": "_planType",
				"type": "string"
			}
		],
		"name": "pay",
		"outputs": [
			{
				"internalType": "uint8",
				"name": "",
				"type": "uint8"
			},
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			},
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "payable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_receipent",
				"type": "address"
			}
		],
		"name": "userCheckStatus",
		"outputs": [
			{
				"internalType": "uint8",
				"name": "",
				"type": "uint8"
			},
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			},
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	}
];

let bytecode = "608060405234801561000f575f80fd5b503360015f6101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550734adc67696ba383f43dd60a9e78f2c97fbbfc7cb15f806101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550610aea806100b05f395ff3fe608060405260043610610033575f3560e01c8063669b680c14610037578063a3e6ba9414610075578063fb4da5b71461009f575b5f80fd5b348015610042575f80fd5b5061005d60048036038101906100589190610610565b6100d1565b60405161006c9392919061066e565b60405180910390f35b348015610080575f80fd5b506100896101b9565b60405161009691906106bb565b60405180910390f35b6100b960048036038101906100b49190610810565b610254565b6040516100c89392919061066e565b60405180910390f35b5f805f8060025f8673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f205f9054906101000a900460ff16610129575f61012c565b60015b90508060035f8773ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f205460045f8873ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f2054935093509350509193909250565b5f805f8054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1663feaf968c6040518163ffffffff1660e01b815260040160a060405180830381865afa158015610223573d5f803e3d5ffd5b505050506040513d601f19601f8201168201806040525081019061024791906108fd565b5050509150508091505090565b5f805f803411610299576040517f08c379a0000000000000000000000000000000000000000000000000000000008152600401610290906109ce565b60405180910390fd5b6102a48534866102b1565b9250925092509250925092565b5f805f8060025f8873ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f205f9054906101000a900460ff1690505f60045f8973ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f20549050600115158215151415801561035657508042115b1561056157600160025f8a73ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f205f6101000a81548160ff0219169083151502179055508660035f8a73ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f20819055506040518060400160405280600781526020017f4d6f6e74686c79000000000000000000000000000000000000000000000000008152508051906020012086805190602001200361044b5762278d006005819055506104fc565b6040518060400160405280600a81526020017f68616c66596561726c7900000000000000000000000000000000000000000000815250805190602001208680519060200120036104a45762ed4e006005819055506104fb565b6040518060400160405280600681526020017f596561726c790000000000000000000000000000000000000000000000000000815250805190602001208680519060200120036104fa576301e133806005819055505b5b5b6005544261050a9190610a19565b60045f8a73ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020019081526020015f2081905550610554886100d1565b945094509450505061059c565b6040517f08c379a000000000000000000000000000000000000000000000000000000000815260040161059390610a96565b60405180910390fd5b93509350939050565b5f604051905090565b5f80fd5b5f80fd5b5f73ffffffffffffffffffffffffffffffffffffffff82169050919050565b5f6105df826105b6565b9050919050565b6105ef816105d5565b81146105f9575f80fd5b50565b5f8135905061060a816105e6565b92915050565b5f60208284031215610625576106246105ae565b5b5f610632848285016105fc565b91505092915050565b5f60ff82169050919050565b6106508161063b565b82525050565b5f819050919050565b61066881610656565b82525050565b5f6060820190506106815f830186610647565b61068e602083018561065f565b61069b604083018461065f565b949350505050565b5f819050919050565b6106b5816106a3565b82525050565b5f6020820190506106ce5f8301846106ac565b92915050565b5f80fd5b5f80fd5b5f601f19601f8301169050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52604160045260245ffd5b610722826106dc565b810181811067ffffffffffffffff82111715610741576107406106ec565b5b80604052505050565b5f6107536105a5565b905061075f8282610719565b919050565b5f67ffffffffffffffff82111561077e5761077d6106ec565b5b610787826106dc565b9050602081019050919050565b828183375f83830152505050565b5f6107b46107af84610764565b61074a565b9050828152602081018484840111156107d0576107cf6106d8565b5b6107db848285610794565b509392505050565b5f82601f8301126107f7576107f66106d4565b5b81356108078482602086016107a2565b91505092915050565b5f8060408385031215610826576108256105ae565b5b5f610833858286016105fc565b925050602083013567ffffffffffffffff811115610854576108536105b2565b5b610860858286016107e3565b9150509250929050565b5f69ffffffffffffffffffff82169050919050565b6108888161086a565b8114610892575f80fd5b50565b5f815190506108a38161087f565b92915050565b6108b2816106a3565b81146108bc575f80fd5b50565b5f815190506108cd816108a9565b92915050565b6108dc81610656565b81146108e6575f80fd5b50565b5f815190506108f7816108d3565b92915050565b5f805f805f60a08688031215610916576109156105ae565b5b5f61092388828901610895565b9550506020610934888289016108bf565b9450506040610945888289016108e9565b9350506060610956888289016108e9565b925050608061096788828901610895565b9150509295509295909350565b5f82825260208201905092915050565b7f596f75206d7573742073656e6420736f6d6520457468657200000000000000005f82015250565b5f6109b8601883610974565b91506109c382610984565b602082019050919050565b5f6020820190508181035f8301526109e5816109ac565b9050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52601160045260245ffd5b5f610a2382610656565b9150610a2e83610656565b9250828201905080821115610a4657610a456109ec565b5b92915050565b7f7573657220697320616c726561647920706169642100000000000000000000005f82015250565b5f610a80601583610974565b9150610a8b82610a4c565b602082019050919050565b5f6020820190508181035f830152610aad81610a74565b905091905056fea2646970667358221220ff8b188e88189b754bbf4a95f5d7b3c7cb89db940a232d919c8064710a43d3d964736f6c63430008160033";

   // load etherJs library
   import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";
         
 await window.ethereum.request({
   method: "wallet_addEthereumChain",
   params: [{
    chainId: "0x14A34",
     rpcUrls: ["https://base-sepolia.blockpi.network/v1/rpc/public"],
     chainName: "SEPOLIA",
      nativeCurrency: {
        name: "ETH",
        symbol: "ETH",
            decimals: 18
       },
             blockExplorerUrls: ["https://base-sepolia.blockscout.com"]
       }]
});
                
 
await window.ethereum.request({ method: "eth_requestAccounts" });
const accounts = await ethereum.request({ method: "eth_accounts" });
const account = accounts[0];

console.log(account)

const provider = new BrowserProvider(window.ethereum);
                            
const signer = await provider.getSigner();
 
const factory = new ContractFactory(abi,bytecode, signer)
                                             
await factory.deploy(
     {       
         gasLimit :3000000,                                             
     }
).then(async function(response) {  

    console.log(response)
});
 

</script>');

}
*/