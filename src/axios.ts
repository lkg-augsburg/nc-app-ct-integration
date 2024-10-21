import { generateUrl } from "@nextcloud/router";
import axios from '@nextcloud/axios'

axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

export default axios;