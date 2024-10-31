import { faFacebook, faInstagram, faTwitter } from '@fortawesome/free-brands-svg-icons'
import { faEnvelope } from '@fortawesome/free-regular-svg-icons'
import { faLocationDot, faPhone } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

const Footer = () => {
  return (
    <div>
       <div className="footer ">
            <div className="bg-[#393F4E] grid grid-cols-3 gap-10 px-24 border-b border-solid border-gray-500">
                <div className="my-4">
                    <div className="">
                        <h2 className="text-[#C9C9C9] font-bold">Hotline</h2>
                        <p className="text-[#2DC275] font-bold">
                            <FontAwesomeIcon icon={faPhone} className='mr-1'/>
                            1900.6408
                        </p>
                    </div>
                    <div className="my-2">
                        <h2 className="text-[#C9C9C9] font-bold">Email</h2>
                        <p className="text-[#C9C9C9]">
                            <FontAwesomeIcon icon={faEnvelope} className='mr-1'/>
                            support@ticketbox.vn
                        </p>
                    </div>
                    <div className="">
                        <h2 className="text-[#C9C9C9] font-bold">Office</h2>
                        <p className="text-[#C9C9C9]">
                        <FontAwesomeIcon icon={faLocationDot} className='mr-1'/>

                            52 Ut Tich, Ward 4, Tan Binh District, HCMC
                        </p>
                    </div>
                </div>
                <div className="my-4">
                    <div className="">
                        <h2 className="text-[#C9C9C9] font-bold">For Customer</h2>
                        <p className="text-[#C9C9C9]">Customer terms of use</p>
                    </div>
                    <div className="my-2">
                        <h2 className="text-[#C9C9C9] font-bold">For Organizer</h2>
                        <p className="text-[#C9C9C9]">Organizer terms of use</p>
                    </div>
                </div>
                <div className="my-4">
                    <h2 className="text-[#C9C9C9] font-bold">Our Company</h2>
                    <p className="text-[#C9C9C9]">Operational regulations</p>
                    <span className="text-[#C9C9C9]">Information privacy policy</span>
                </div>
            </div>
            <div className="bg-[#393F4E] grid grid-cols-3 gap-10 px-24">
                <div className="my-4">
                    <h1 className="text-white text-3xl">TICKETBOX</h1>
                    <p className="text-[#C9C9C9]">Vietnam's premier ticketing platform for events Ticketbox Co. Ltd. © 2016
                    </p>
                </div>
                <div className="my-4">
                    <p className="text-[#C9C9C9]">Ticketbox Co.Ltd</p>
                    <span className="text-[#C9C9C9]">Legal representative: Tran Ngoc Thai Son</span>
                </div>
                <div className="my-4 flex">
                    <FontAwesomeIcon icon={faFacebook} className='text-[#1877F2] text-3xl ml-4'/>
                    <FontAwesomeIcon icon={faInstagram} className='text-[#DE4380] text-3xl ml-4'/>
                    <FontAwesomeIcon icon={faTwitter} className='text-[#1877F2] text-3xl ml-4'/>
                </div>
            </div>
          </div>
    </div>
  )
}

export default Footer
