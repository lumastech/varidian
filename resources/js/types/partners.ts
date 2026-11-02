export type PartnerStatus = 'pending' | 'active' | 'terminated';

export type LeadStatus =
    | 'pending'
    | 'confirmed'
    | 'engaged'
    | 'won'
    | 'lost'
    | 'rejected';

export type CommissionRates = {
    subscription: number;
    licence: number;
    project: number;
    hosting: number;
    referral: number;
};

export type PartnerLead = {
    id: number;
    organisation: string;
    contactName: string;
    contactPhone: string;
    contactEmail: string | null;
    interest: string;
    notes: string | null;
    status: LeadStatus;
    statusLabel: string;
    reviewNote: string | null;
    isExpired: boolean;
    registeredAt: string;
    expiresAt: string | null;
    wonAt: string | null;
};

export type CommissionLine = {
    id: number;
    type: string;
    typeLabel: string;
    client: string | null;
    description: string;
    received: string | null;
    rate: number | null;
    amount: string;
    earnedAt: string;
    isPaid: boolean;
};

export type PartnerPayout = {
    id: number;
    amount: string;
    method: string;
    reference: string | null;
    paidAt: string;
};
