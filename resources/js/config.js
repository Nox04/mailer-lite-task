export const apiDomain = document.head.querySelector('meta[name="api-domain"]')
  .content;
export const csrfToken = document.head.querySelector('meta[name="csrf-token"]')
  .content;

export const subscriberStates = [
  'ACTIVE',
  'BOUNCED',
  'JUNK',
  'UNCONFIRMED',
  'UNSUBSCRIBED',
];
