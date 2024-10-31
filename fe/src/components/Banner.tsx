
import { BannerImage } from './icon/image'
const Banner = () => {
  return (
    <div>
       <div className="banner mt-2">
                <img src={BannerImage} alt="" className="w-full rounded-3xl" />
            </div>
    </div>
  )
}

export default Banner
