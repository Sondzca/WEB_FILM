
import { MusicImage } from './icon/image'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCakeCandles } from '@fortawesome/free-solid-svg-icons'
import { faArrowCircleRight } from '@fortawesome/free-solid-svg-icons/faArrowCircleRight'
import { Link } from 'react-router-dom'


const Music = ()=> {
    
return(

    <div>
          <div className="my-6">
                <div className="flex justify-between mb-2">
                    <h2 className="font-bold text-xl text-white">
                       
                    </h2>
                    <h3 className="text-lg text-gray-400">
                        View more 
                        <FontAwesomeIcon icon={faArrowCircleRight} className='ml-1'/>
                        
                    </h3>
                </div>
                <div className="grid grid-cols-4 gap-6">
                
                    
                    <div className="">
                        <Link to="detail"><img src={MusicImage} alt="" className="w-full rounded-xl" /></Link>

                        <h3 className="font-bold text-sm mt-2 text-white">LULULOLA SHOW HOÀNG DŨNG KM LÂM PHÚC & MR BOTT BAND | ĐÔI LỜI TÌNH CA</h3>
                        <p className="text-[#2DC275] my-2 font-bold">From 450.000đ</p>
                        <span className="text-white">
                            <FontAwesomeIcon icon={faCakeCandles} className='mr-1'/>
                            19 Jul, 2024
                        </span>
                    </div>
                    <div className="">
                        <Link to="detail"><img src={MusicImage} alt="" className="w-full rounded-xl" /></Link>

                        <h3 className="font-bold text-sm mt-2 text-white">LULULOLA SHOW HOÀNG DŨNG KM LÂM PHÚC & MR BOTT BAND | ĐÔI LỜI TÌNH CA</h3>
                        <p className="text-[#2DC275] my-2 font-bold">From 450.000đ</p>
                         <span className="text-white">
                            <FontAwesomeIcon icon={faCakeCandles} className='mr-1'/>
                            19 Jul, 2024
                        </span>
                    </div>
                    <div className="">
                        <Link to="detail"><img src={MusicImage} alt="" className="w-full rounded-xl" /></Link>
                        <h3 className="font-bold text-sm mt-2 text-white">LULULOLA SHOW HOÀNG DŨNG KM LÂM PHÚC & MR BOTT BAND | ĐÔI LỜI TÌNH CA</h3>
                        <p className="text-[#2DC275] my-2 font-bold">From 450.000đ</p>
                         <span className="text-white">
                            <FontAwesomeIcon icon={faCakeCandles} className='mr-1'/>
                            19 Jul, 2024
                        </span>
                    </div>
                </div>
            </div>
    </div>
  )
}

export default Music
