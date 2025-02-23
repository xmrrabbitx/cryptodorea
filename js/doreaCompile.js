/**
 * Smart Contract Structure
 * you could see the source code of compiled solidity
 * on /contracts/doreaLoyalty.sol Directory of the plugin
 * @type {[{inputs: *[], stateMutability: string, type: string},{outputs: *[], inputs: [{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string}], name: string, stateMutability: string, type: string},{outputs: *[], inputs: [{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string}], name: string, stateMutability: string, type: string},{outputs: [{name: string, internalType: string, type: string}], inputs: *[], name: string, stateMutability: string, type: string},{outputs: *[], inputs: [{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},{name: string, internalType: string, type: string},null], name: string, stateMutability: string, type: string}]}
 */
let abi = [
    {
        "inputs": [],
        "stateMutability": "payable",
        "type": "constructor"
    },
    {
        "inputs": [
            {
                "internalType": "bytes32",
                "name": "_hashedMessage",
                "type": "bytes32"
            },
            {
                "internalType": "uint8",
                "name": "_v",
                "type": "uint8"
            },
            {
                "internalType": "bytes32",
                "name": "_r",
                "type": "bytes32"
            },
            {
                "internalType": "bytes32",
                "name": "_s",
                "type": "bytes32"
            }
        ],
        "name": "adminAuthorized",
        "outputs": [],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "bytes32",
                "name": "trxId",
                "type": "bytes32"
            }
        ],
        "name": "checkTrxIds",
        "outputs": [
            {
                "internalType": "bool",
                "name": "",
                "type": "bool"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "bytes32",
                "name": "_hashedMessage",
                "type": "bytes32"
            },
            {
                "internalType": "uint8",
                "name": "_v",
                "type": "uint8"
            },
            {
                "internalType": "bytes32",
                "name": "_r",
                "type": "bytes32"
            },
            {
                "internalType": "bytes32",
                "name": "_s",
                "type": "bytes32"
            }
        ],
        "name": "fundAgain",
        "outputs": [],
        "stateMutability": "payable",
        "type": "function"
    },
    {
        "inputs": [],
        "name": "getBalance",
        "outputs": [
            {
                "internalType": "uint256",
                "name": "",
                "type": "uint256"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "address[]",
                "name": "recipients",
                "type": "address[]"
            },
            {
                "internalType": "uint256[]",
                "name": "amounts",
                "type": "uint256[]"
            },
            {
                "internalType": "bytes32",
                "name": "trxId",
                "type": "bytes32"
            },
            {
                "internalType": "bytes32",
                "name": "_hashedMessage",
                "type": "bytes32"
            },
            {
                "internalType": "uint8",
                "name": "_v",
                "type": "uint8"
            },
            {
                "internalType": "bytes32",
                "name": "_r",
                "type": "bytes32"
            },
            {
                "internalType": "bytes32",
                "name": "_s",
                "type": "bytes32"
            }
        ],
        "name": "pay",
        "outputs": [],
        "stateMutability": "payable",
        "type": "function"
    }
];

let bytecode = "60806040527315cddccf29a3d2653cca38f4d752bd78171fa1805f806101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff160217905550600a6001553360025f6101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff1602179055505f805f9054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff166108fc606460015434620000e9919062000190565b620000f5919062000207565b90811502906040515f60405180830381858888f1935050505090508062000153576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004016200014a906200029c565b60405180910390fd5b50620002bc565b5f819050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52601160045260245ffd5b5f6200019c826200015a565b9150620001a9836200015a565b9250828202620001b9816200015a565b91508282048414831517620001d357620001d262000163565b5b5092915050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52601260045260245ffd5b5f62000213826200015a565b915062000220836200015a565b925082620002335762000232620001da565b5b828204905092915050565b5f82825260208201905092915050565b7f5472616e73666572206661696c656400000000000000000000000000000000005f82015250565b5f62000284600f836200023e565b915062000291826200024e565b602082019050919050565b5f6020820190508181035f830152620002b58162000276565b9050919050565b610bad80620002ca5f395ff3fe608060405260043610610049575f3560e01c806303c1f7291461004d57806312065fe014610075578063433dd8531461009f5780634990f17c146100bb578063af296ddc146100f7575b5f80fd5b348015610058575f80fd5b50610073600480360381019061006e9190610492565b610113565b005b348015610080575f80fd5b5061008961025f565b604051610096919061050e565b60405180910390f35b6100b960048036038101906100b49190610492565b610266565b005b3480156100c6575f80fd5b506100e160048036038101906100dc9190610527565b610278565b6040516100ee919061056c565b60405180910390f35b610111600480360381019061010c9190610819565b6102cf565b005b5f6040518060400160405280601c81526020017f19457468657265756d205369676e6564204d6573736167653a0a33320000000081525090505f818660405160200161016092919061097a565b6040516020818303038152906040528051906020012090505f6001828787876040515f815260200160405260405161019b94939291906109bf565b6020604051602081039080840390855afa1580156101bb573d5f803e3d5ffd5b50505060206040510351905060025f9054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168173ffffffffffffffffffffffffffffffffffffffff1614610256576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040161024d90610a5c565b60405180910390fd5b50505050505050565b5f47905090565b61027284848484610113565b50505050565b5f805f90505b6003805490508110156102c55782600382815481106102a05761029f610a7a565b5b905f5260205f200154036102b85760019150506102ca565b808060010191505061027e565b505f90505b919050565b6102db84848484610113565b5f6102e461025f565b03610324576040517f08c379a000000000000000000000000000000000000000000000000000000000815260040161031b90610af1565b60405180910390fd5b5f5b87518110156103e8575f88828151811061034357610342610a7a565b5b602002602001015173ffffffffffffffffffffffffffffffffffffffff166108fc89848151811061037757610376610a7a565b5b602002602001015190811502906040515f60405180830381858888f193505050509050806103da576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004016103d190610b59565b60405180910390fd5b508080600101915050610326565b50600385908060018154018082558091505060019003905f5260205f20015f909190919091505550505050505050565b5f604051905090565b5f80fd5b5f80fd5b5f819050919050565b61043b81610429565b8114610445575f80fd5b50565b5f8135905061045681610432565b92915050565b5f60ff82169050919050565b6104718161045c565b811461047b575f80fd5b50565b5f8135905061048c81610468565b92915050565b5f805f80608085870312156104aa576104a9610421565b5b5f6104b787828801610448565b94505060206104c88782880161047e565b93505060406104d987828801610448565b92505060606104ea87828801610448565b91505092959194509250565b5f819050919050565b610508816104f6565b82525050565b5f6020820190506105215f8301846104ff565b92915050565b5f6020828403121561053c5761053b610421565b5b5f61054984828501610448565b91505092915050565b5f8115159050919050565b61056681610552565b82525050565b5f60208201905061057f5f83018461055d565b92915050565b5f80fd5b5f601f19601f8301169050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52604160045260245ffd5b6105cf82610589565b810181811067ffffffffffffffff821117156105ee576105ed610599565b5b80604052505050565b5f610600610418565b905061060c82826105c6565b919050565b5f67ffffffffffffffff82111561062b5761062a610599565b5b602082029050602081019050919050565b5f80fd5b5f73ffffffffffffffffffffffffffffffffffffffff82169050919050565b5f61066982610640565b9050919050565b6106798161065f565b8114610683575f80fd5b50565b5f8135905061069481610670565b92915050565b5f6106ac6106a784610611565b6105f7565b905080838252602082019050602084028301858111156106cf576106ce61063c565b5b835b818110156106f857806106e48882610686565b8452602084019350506020810190506106d1565b5050509392505050565b5f82601f83011261071657610715610585565b5b813561072684826020860161069a565b91505092915050565b5f67ffffffffffffffff82111561074957610748610599565b5b602082029050602081019050919050565b610763816104f6565b811461076d575f80fd5b50565b5f8135905061077e8161075a565b92915050565b5f6107966107918461072f565b6105f7565b905080838252602082019050602084028301858111156107b9576107b861063c565b5b835b818110156107e257806107ce8882610770565b8452602084019350506020810190506107bb565b5050509392505050565b5f82601f830112610800576107ff610585565b5b8135610810848260208601610784565b91505092915050565b5f805f805f805f60e0888a03121561083457610833610421565b5b5f88013567ffffffffffffffff81111561085157610850610425565b5b61085d8a828b01610702565b975050602088013567ffffffffffffffff81111561087e5761087d610425565b5b61088a8a828b016107ec565b965050604061089b8a828b01610448565b95505060606108ac8a828b01610448565b94505060806108bd8a828b0161047e565b93505060a06108ce8a828b01610448565b92505060c06108df8a828b01610448565b91505092959891949750929550565b5f81519050919050565b5f81905092915050565b5f5b8381101561091f578082015181840152602081019050610904565b5f8484015250505050565b5f610934826108ee565b61093e81856108f8565b935061094e818560208601610902565b80840191505092915050565b5f819050919050565b61097461096f82610429565b61095a565b82525050565b5f610985828561092a565b91506109918284610963565b6020820191508190509392505050565b6109aa81610429565b82525050565b6109b98161045c565b82525050565b5f6080820190506109d25f8301876109a1565b6109df60208301866109b0565b6109ec60408301856109a1565b6109f960608301846109a1565b95945050505050565b5f82825260208201905092915050565b7f55736572206973206e6f7420417574686f72697a6564210000000000000000005f82015250565b5f610a46601783610a02565b9150610a5182610a12565b602082019050919050565b5f6020820190508181035f830152610a7381610a3a565b9050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52603260045260245ffd5b7f62616c616e6365206973207a65726f21000000000000000000000000000000005f82015250565b5f610adb601083610a02565b9150610ae682610aa7565b602082019050919050565b5f6020820190508181035f830152610b0881610acf565b9050919050565b7f5472616e73666572206661696c656421000000000000000000000000000000005f82015250565b5f610b43601083610a02565b9150610b4e82610b0f565b602082019050919050565b5f6020820190508181035f830152610b7081610b37565b905091905056fea2646970667358221220e300290446a0db1d424a7c285693ada75e0d8c870ccd4b0d395409bb2015643164736f6c63430008160033";

export {abi,bytecode};