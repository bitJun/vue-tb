const baseDomain = Laravel.baseDomain;
const apiDomain = Laravel.apiDomain;

export const login = baseDomain + '/auth/login.json';
export const logout = baseDomain + '/auth/logout.json';
export const currentUser = apiDomain + '/user.json';
export const getRegions = apiDomain + '/regions.json';
export const getTimelines = apiDomain + '/timelines.json';
export const postTimeline = apiDomain + '/timeline.json';
export const editTimeline = apiDomain + '/timeline/{id}.json';
export const getTimeline = apiDomain + '/timeline/{id}.json';
export const getQiniuToken = apiDomain + '/qiniu/token.json';
export const editShop = apiDomain + '/shop.json';
export const getShop = apiDomain + '/shop.json';
export const getMembers = apiDomain + '/members.json';
export const getOrders = apiDomain + '/orders.json';
export const getOrder = apiDomain + '/order/{id}.json';
export const getCreditDetails = apiDomain + '/credit/details.json';
export const getBalanceDetails = apiDomain + '/balance/details.json';
export const editCreditRule = apiDomain + '/credit_rule.json';
export const getCreditRule = apiDomain + '/credit_rule.json';
export const editPassword = apiDomain + '/password.json';
export const getBanks = apiDomain + '/banks.json';
export const getBankCards = apiDomain + '/bank_cards.json';
export const getBankCard = apiDomain + '/bank_card/{id}.json';
export const addBankCard = apiDomain + '/bank_card.json';
export const editBankCard = apiDomain + '/bank_card/{id}.json';
export const deleteBankCard = apiDomain + '/bank_card/{id}.json';
export const getShopBalanceDetails = apiDomain + '/shop/balance_details.json';
export const postShopWithdraw = apiDomain + '/shop/withdraw.json';
export const getShopWithdraws = apiDomain + '/shop/withdraws.json';
export const getShopWithdrawStatus = apiDomain + '/shop/withdraw_status.json';
export const getShopQrcodes = apiDomain + '/shop/qrcodes.json';
export const postSmssend = apiDomain + '/llpay/smssend.json';
export const postSmscheck = apiDomain + '/llpay/smscheck.json';
export const postOpensmsunituser = apiDomain + '/llpay/opensmsunituser.json';//企业用户短信开户
//export const postBankcardOpenAuth = apiDomain + '/llpay/bankcardOpenAuth.json';//个体工商户绑卡认证
export const postBankcardAuthVerfy = apiDomain + '/llpay/bankcardAuthVerfy.json';//个体工商户银行卡绑卡验证
export const postPwdAuth = apiDomain + '/llpay/pwdauth.json';//更新验证接口
export const putUnitUser = apiDomain + '/llpay/modifyunituser.json';//修改基本信息
export const putUnitUserAcct = apiDomain + '/llpay/modiyunituseracct.json';//修改对公接口
export const uploadUnitPhoto = apiDomain + '/llpay/uploadunitphoto.json';//上传图片信息
export const getLlpayInfo = apiDomain + '/llpay/info.json';
export const getSingleUser = apiDomain + '/llpay/single_user.json';
export const editMemberLevel = apiDomain + '/member_level.json';
export const getMemberLevel = apiDomain + '/member_level.json';
export const qiniuUploadAction = apiDomain + '/qiniu/upload';
