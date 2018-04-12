const baseDomain = Modian.baseDomain;
const apiDomain = Modian.apiDomain;

export const login = baseDomain + '/auth/login.json';
export const logout = baseDomain + '/auth/logout.json';
export const currentUser = apiDomain + '/user.json';

//facilitator
export const facilitatorInfo = apiDomain + '/facilitator/info.json';
export const facilitatorList = apiDomain + '/facilitator/list.json';
export const facilitatorEdit = apiDomain + '/facilitator/{id}.json';
export const facilitatorStore = apiDomain + '/facilitator/store.json';
export const facilitatorQuery = apiDomain + '/facilitator/query.json';


//shop
export const shops = apiDomain + '/shops.json';

export const regionList = apiDomain + '/regions_list.json';


//shop
export const getshops = apiDomain + '/shops.json'
export const postshop = apiDomain + '/shop.json'
export const getshopdetail = apiDomain + '/shop/{id}.json'
export const gettags = apiDomain + '/tags.json'
export const getregions = apiDomain + '/regions.json?parent_id={id}';

//setting
export const editPassword = apiDomain + '/pass.json';
export const uploadUnitPhoto = apiDomain + '/llpay/uploadunitphoto.json';//上传图片信息
export const qiniuUploadAction = apiDomain + '/qiniu/upload';
export const getQiniuToken = apiDomain + '/qiniu/token.json';

//moker
export const getMokerList =apiDomain + '/mokerlist.json'
export const getMokerDetail = apiDomain + '/moker/{id}.json';

//trade
export const getCommissionlist = apiDomain + '/commissionlist.json';

//Withdrawals
export const Withdrawals = apiDomain + '/Withdrawals.json';
export const BankList = apiDomain + '/bank.json';

//index
export const Index = apiDomain + '/index.json';