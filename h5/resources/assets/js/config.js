const apiDomain = Modian.apiDomain;

export const getShop = apiDomain + '/shop/{token}.json';
export const getOrder = apiDomain + '/order/{sid}/{osn}.json';
export const postOrder = apiDomain + '/order.json';
export const sendVerifyCode = apiDomain + '/sms/send_verify_code.json';
export const giveCredit = apiDomain + '/give_credit.json';
export const bindMember = apiDomain + '/bind_member.json';