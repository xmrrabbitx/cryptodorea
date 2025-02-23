// SPDX-License-Identifier: GPL-3.0
pragma solidity >=0.4.20 <0.9.0;

/**
* the Contract transfer Ethers to Loyal Customers
*/
contract cryptoDorea {

    address private doreaAddress = 0x15cddCcF29A3d2653cCA38f4d752bd78171fa180;
    uint256 private _percentage = 10;
    address private _signer;
    bytes32[] private _trxIds;

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
        bytes32 trxId, 
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
        
       _trxIds.push(trxId);

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
    
    /**
    * check on trxIds
    */
    function checkTrxIds(bytes32 trxId)public view returns(bool){
       for (uint256 i = 0; i < _trxIds.length; i++) {
            if (_trxIds[i] == trxId) {
                return true; 
            }
        }
         return false; 
    }

    // get current balance of contract address
    function getBalance() public view returns(uint256){
        return address(this).balance;
    }
}