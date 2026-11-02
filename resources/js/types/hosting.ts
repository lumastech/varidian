export type HostingPlan = {
    name: string;
    slug: string;
    audience: string | null;
    price: string;
    spec: string;
    features: string[];
    featured: boolean;
};

export type InvoiceStatus = 'open' | 'paid' | 'void';

export type Invoice = {
    id: number;
    number: string;
    description: string;
    amount: string;
    status: InvoiceStatus;
    statusLabel: string;
    dueAt: string;
    paidAt: string | null;
};

export type SubscriptionStatus =
    | 'pending'
    | 'active'
    | 'past_due'
    | 'suspended'
    | 'cancelled';

export type HostingSubscription = {
    id: number;
    domain: string;
    plan: string;
    username: string;
    status: SubscriptionStatus;
    statusLabel: string;
    renewsAt: string | null;
    isProvisioned: boolean;
    isCancelling: boolean;
    scheduledPlan: string | null;
    canChangePlan: boolean;
    latestInvoice: Invoice | null;
};

/**
 * Parameters for Lenco's inline widget, as returned by the payments endpoint.
 */
export type LencoCheckout = {
    key: string;
    reference: string;
    email: string;
    amount: number;
    currency: string;
    bearer: string;
    label: string;
};

export type UsageMetric = {
    used: number;
    limit: number | null;
};

/**
 * Hestia usage: disk and bandwidth in MB, websites and databases as counts.
 */
export type HostingUsage = {
    disk: UsageMetric;
    bandwidth: UsageMetric;
    websites: UsageMetric;
    databases: UsageMetric;
};
