<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Contract;

class TraceController extends Controller
{
    public function verify($gtin, $batchCode)
    {
        // 1. TÌM SẢN PHẨM & LÔ HÀNG TỪ MÃ QR
        $product = Product::where('gtin_code', $gtin)->first();
        if (!$product) {
            return abort(404, 'Không tìm thấy thông tin Sản phẩm!');
        }

        $batch = Batch::where('batch_code', $batchCode)->where('product_id', $product->id)->first();
        if (!$batch) {
            return abort(404, 'Không tìm thấy thông tin Lô hàng!');
        }

        // 2. TẠO MÃ BĂM (HASH) TỪ MYSQL (Phải y hệt như lúc Mint)
        $payload = [
            'batch_id' => $batch->id,
            'batch_code' => $batch->batch_code,
            'product_name' => $product->name,
            'manufacturing_date' => $batch->manufacturing_date,
            'expiry_date' => $batch->expiry_date,
        ];
        $mysqlHash = '0x' . hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE));

        // 3. KẾT NỐI SMART CONTRACT LẤY HASH GỐC
        $blockchainHash = null;
        try {
            $web3 = new Web3(env('BLOCKCHAIN_RPC_URL'));
            // LƯU Ý: Nhớ dán ABI của bạn vào chỗ này
            $contract = new Contract($web3->provider, '[
                                                        {
                                                            "inputs": [],
                                                            "stateMutability": "nonpayable",
                                                            "type": "constructor"
                                                        },
                                                        {
                                                            "anonymous": false,
                                                            "inputs": [
                                                                {
                                                                    "indexed": false,
                                                                    "internalType": "string",
                                                                    "name": "batchCode",
                                                                    "type": "string"
                                                                },
                                                                {
                                                                    "indexed": false,
                                                                    "internalType": "string",
                                                                    "name": "dataHash",
                                                                    "type": "string"
                                                                },
                                                                {
                                                                    "indexed": false,
                                                                    "internalType": "uint256",
                                                                    "name": "timestamp",
                                                                    "type": "uint256"
                                                                }
                                                            ],
                                                            "name": "BatchMinted",
                                                            "type": "event"
                                                        },
                                                        {
                                                            "inputs": [
                                                                {
                                                                    "internalType": "string",
                                                                    "name": "_batchCode",
                                                                    "type": "string"
                                                                },
                                                                {
                                                                    "internalType": "string",
                                                                    "name": "_dataHash",
                                                                    "type": "string"
                                                                }
                                                            ],
                                                            "name": "mintBatch",
                                                            "outputs": [],
                                                            "stateMutability": "nonpayable",
                                                            "type": "function"
                                                        },
                                                        {
                                                            "inputs": [],
                                                            "name": "admin",
                                                            "outputs": [
                                                                {
                                                                    "internalType": "address",
                                                                    "name": "",
                                                                    "type": "address"
                                                                }
                                                            ],
                                                            "stateMutability": "view",
                                                            "type": "function"
                                                        },
                                                        {
                                                            "inputs": [
                                                                {
                                                                    "internalType": "string",
                                                                    "name": "_batchCode",
                                                                    "type": "string"
                                                                }
                                                            ],
                                                            "name": "verifyBatch",
                                                            "outputs": [
                                                                {
                                                                    "internalType": "string",
                                                                    "name": "",
                                                                    "type": "string"
                                                                }
                                                            ],
                                                            "stateMutability": "view",
                                                            "type": "function"
                                                        }
                                                    ]'); 

            // Dùng hàm 'call' để đọc dữ liệu (Hoàn toàn miễn phí, không tốn Gas)
            $contract->at(env('BLOCKCHAIN_CONTRACT_ADDRESS'))->call('verifyBatch', $batchCode, function ($err, $result) use (&$blockchainHash) {
                if ($err === null && isset($result[0])) {
                    $blockchainHash = $result[0];
                }
            });
        } catch (\Exception $e) {
            // Nếu mạng lưới lỗi, ghi nhận trạng thái lỗi để báo cho khách
            $blockchainHash = 'NETWORK_ERROR'; 
        }

        // 4. KIỂM TRA ĐỐI CHIẾU
        if ($blockchainHash === 'NETWORK_ERROR') {
            $verifyStatus = 'error';
            $message = 'Lỗi kết nối đến Sổ cái Blockchain. Không thể xác thực lúc này!';
        } elseif ($blockchainHash === $mysqlHash) {
            $verifyStatus = 'success';
            $message = 'SẢN PHẨM CHÍNH HÃNG - Dữ liệu đã được xác thực toàn vẹn trên Blockchain.';
        } else {
            $verifyStatus = 'warning';
            $message = 'CẢNH BÁO: Dữ liệu sản phẩm không trùng khớp với Sổ cái Blockchain. Vui lòng cẩn thận!';
        }

        // 5. TRẢ VỀ GIAO DIỆN QUÉT MÃ CỦA KHÁCH HÀNG
        // (Bạn cần tạo file resources/views/customer/scan_result.blade.php để hiển thị giao diện này)
        return view('customer.scan_result', compact('batch', 'product', 'verifyStatus', 'message', 'mysqlHash', 'blockchainHash'));
    }
}