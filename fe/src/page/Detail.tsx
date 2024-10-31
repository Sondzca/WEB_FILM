import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { BannerDetail, BannerEvent, BannerFestival } from "../components/icon/image";
import { faCakeCandles, faLocationDot } from "@fortawesome/free-solid-svg-icons";
import { useState, useEffect, useCallback,FC,useMemo } from 'react';
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";
import { toast } from "react-toastify";
import { formatDate, formatPrice } from "../utils/format";
import { useWallet, WalletProvider, ConnectionProvider } from "@solana/wallet-adapter-react";
import { clusterApiUrl, SystemProgram, Transaction, Keypair, Connection, LAMPORTS_PER_SOL, PublicKey } from '@solana/web3.js';
import { WalletAdapterNetwork } from '@solana/wallet-adapter-base';
import { WalletModalProvider, WalletMultiButton } from '@solana/wallet-adapter-react-ui';
import { UnsafeBurnerWalletAdapter } from '@solana/wallet-adapter-wallets';


const DetailCompoment:FC = () => {
    const { publicKey, sendTransaction, connected } = useWallet();
    const [isCollapsed, setIsCollapsed] = useState(false);
    const [tickets, setTickets] = useState([]);
    const { id } = useParams()
    const [categoryName, setCategoryName] = useState('');
    const navigate = useNavigate();
    useEffect(() => {
        const fetchTickets = async () => {
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/ticket/detail/${id}`);
                setTickets(response.data.tickets);
                // console.log(tickets);
                
            } catch (error) {
                console.error('Error fetching tickets:', error);
            }
        };
        fetchTickets();
        // 
    }, [id]);

    const handleBuyTicket = useCallback(async (ticket: any) => {
        if (!connected || !publicKey) {
            toast.warning('Please connect your wallet first!');
            return;
        }
        // Transfer
        const connection = new Connection(clusterApiUrl(WalletAdapterNetwork.Devnet));
        const toPublicKey = new PublicKey('2KApVahSsRytYNN1ovEoKTNbXodtLgzkxSFLGMgn44uK');
        const transaction = new Transaction().add(
            SystemProgram.transfer({
                fromPubkey: publicKey,
                toPubkey: toPublicKey,
                lamports: (ticket.giatien* LAMPORTS_PER_SOL)/1000000,
            })
        );

        try {
            // Transfer SOL
            const signature = await sendTransaction(transaction, connection);
            await connection.confirmTransaction(signature, "processed");
            // console.log('Transaction successful with signature:', signature);

            //End transfer

             // Post ticket
            const postData = {
                wallet: publicKey.toString(),
                id: ticket.id,
                soluong: "1",
            };

            console.log('Sending postData:', postData);

            const response = await axios.post('http://127.0.0.1:8000/api/Ticket/add/gameshift',postData);
            console.log("Add ticket successfully", response.data);
            toast.success('Ticket purchased successfully!');
            // navigate('#', { state: { postData: postData } });
        } catch (error) {
            console.error('Transaction failed:', error);
            if (error) {
                console.log('API response error:', error);
                
            } else {
                toast.error('Transaction failed. Please try again.');
            }
        }
    
    }, [connected, publicKey, sendTransaction]);

  
    return (
        <div>
            {tickets.map((ticket: any) => (
                <div className="grid grid-cols-[40%,1fr]" key={ticket.id}>
                    <div className="bg-[#38383D] p-4 rounded-xl">
                        <div className="min-h-[70%] border-b border-solid border-white">
                            <h1 className="text-xl text-white font-bold">{ticket.name}</h1>
                            <p className="text-sm text-[#2DC275] font-bold my-2">
                                <FontAwesomeIcon icon={faCakeCandles} className='mr-1' />
                                Thời gian bắt đầu :{formatDate(ticket.ngayphathanh)}
                            </p>
                            <p className="text-sm text-[#2DC275] font-bold my-2">
                                <FontAwesomeIcon icon={faCakeCandles} className='mr-1' />
                                Thời gian kết thúc :{formatDate(ticket.ngayketthuc)}
                            </p>
                            <p className="text-sm text-[#2DC275] font-bold my-2">
                                <FontAwesomeIcon icon={faLocationDot} className='mr-1' />
                                Nơi tổ chức: {ticket.noitochuc}
                            </p>
                        </div>
                        <div className="my-4">
                            <h3 className="text-white text-lg font-bold">Từ <a href="" className="text-[#2DC275] text-lg font-bold">{formatPrice(ticket.giatien/1000000)} SOL</a></h3>
                            <button 
                                className="w-full my-2 bg-[#2DC275] py-1 rounded-sm text-white font-bold hover:bg-white hover:text-black"
                                onClick={() => handleBuyTicket(ticket)}
                            >
                            Mua vé ngay
                            </button>
                        </div>
                    </div>
                    <div className="">
                        <img src={ticket.url} alt="" className="w-full rounded-xl" />
                    </div>
                </div>
            ))}

            {tickets.map((ticket: any) => (
                <div className="grid grid-cols-[70%,1fr] gap-6 px-24 my-6 bg-[#F5F7FC]" key={ticket.id}>
                    <div className="">
                        <div className="bg-white rounded-lg my-4 p-2 ">
                            <div className="border-b border-solid border-gray-300 py-2 font-bold">
                                <h1>Giới thiệu</h1>
                            </div>
                            <div className={`my-4 introduction ${isCollapsed ? 'collapsed' : ''}`}>
                                <h2 className="text-center font-bold my-4">{ticket.name}</h2>
                                {!isCollapsed && (
                                    <p>{ticket.mota}</p>
                                )}
                                <img src={BannerFestival} alt="" />
                            </div>
                            <button className="toggle-button rounded" onClick={() => setIsCollapsed(!isCollapsed)}>
                                {isCollapsed ? 'Xem thêm' : 'Thu gọn'}
                            </button>
                        </div>
                        <div className="border-b border-solid border-black p-2">
                            <h1 className="font-bold text-lg">Thông tin vé</h1>
                        </div>
                        <div className="ticketing-interface" style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '20px' }}>
                            <div>
                                {/* <h3 className="text-left font-bold my-2">Loại vé</h3>
                                <p className="text-left text-sm">{ca}</p> */}
                                <h3 className="text-left font-bold my-2">Thời gian bắt đầu</h3>
                                <p className="text-left text-sm">{ticket.ngayphathanh}</p>
                                <h3 className="text-left font-bold my-2">Thời gian kết thúc</h3>
                                <p className="text-left text-sm">{ticket.ngayketthuc}</p>
                            </div>
                            <div>
                                <h3 className="text-left font-bold my-2">Ban Tổ Chức</h3>
                                <p className="text-left text-sm">{ticket.nguoitochuc}</p>
                                <h3 className="text-left font-bold my-2">Nơi tổ chức</h3>
                                <p className="text-left text-sm">{ticket.noitochuc}</p>
                                <h3 className="text-left font-bold my-2">Địa chỉ</h3>
                                <p className="text-left text-sm">{ticket.diachi}</p>
                            </div>
                        </div>
                        <div className="bg-white rounded-lg my-2 p-2 "></div>
                    </div>
                    <div className="bg-[#F5F7FC] max-h-[70%] my-4">
                        <img src={BannerEvent} alt="" className=" rounded-xl" />
                    </div>
                </div>
            ))}
        </div>
    );
};
const Detail: FC = () => {

    const network = WalletAdapterNetwork.Devnet;
    const endpoint = useMemo(() => clusterApiUrl(network), [network]);

    const wallets = useMemo(
        () => [
            new UnsafeBurnerWalletAdapter(),
        ],
        [network]
    );

    return (
        <ConnectionProvider endpoint={endpoint}>
            <WalletProvider wallets={wallets} autoConnect>
                <WalletModalProvider>
                    <DetailCompoment />
                </WalletModalProvider>
            </WalletProvider>
        </ConnectionProvider>
    );

}

export default Detail;
