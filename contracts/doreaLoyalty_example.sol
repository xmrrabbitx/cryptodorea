// SPDX-License-Identifier: GPL-3.0
pragma solidity >=0.4.20 <0.9.0;

/**
* the Contract transfer Ethers to Loyal Customers
*/
contract cryptoDorea {

    address private doreaAddress = 0xca578e925551aCB0d86D3557a6fF26a68034C88b;
    uint256 private _percentage = 10;
    address private _signer;

    /**
    * fund on deployment
    */ 
    constructor() payable {

        _signer = msg.sender;

        // Transfer Ether to the receipnt address of dorea
        bool success = payable(doreaAddress).send((msg.value * _percentage) / 100);
        require(success, "Transfer failed");

    }

    /**
    * Transfer Ethers to Loyal Customers
    */
    function pay(

        address[] memory recipients, 
        uint256[] memory amounts, 
        bytes32 _hashedMessage, 
        uint8 _v, 
        bytes32 _r, 
        bytes32 _s

    ) public payable{

        adminAuthorized(_hashedMessage, _v, _r, _s);

        if(getBalance() == 0){
            revert("balance is zero!");
        }

        for( uint256 i=0; i < recipients.length; i++ ){

            // Transfer Ether to the receipnt address
            bool success = payable(recipients[i]).send(amounts[i]);
            require(success, "Transfer failed!");

        }
        
    }

    /**
    * fund campaign again
    */
    function fundAgain(
        
        bytes32 _hashedMessage,   
        uint8 _v, 
        bytes32 _r, 
        bytes32 _s

    )public payable{

       adminAuthorized(_hashedMessage, _v, _r, _s);

    }

    /**
    * check if admin is authorized to pay
    */
    function adminAuthorized(bytes32 _hashedMessage, uint8 _v, bytes32 _r, bytes32 _s) public view {
        
        bytes memory prefix = "\x19Ethereum Signed Message:\n32";

        bytes32 prefixedHashMessage = keccak256(abi.encodePacked(prefix, _hashedMessage));
        address signer = ecrecover(prefixedHashMessage, _v, _r, _s);

        if(signer != _signer){
            revert("User is not Authorized!");
        }

    }

    // get current balance of contract address
    function getBalance() public view returns(uint256){
        return address(this).balance;
    }
}