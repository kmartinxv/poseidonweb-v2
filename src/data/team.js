import chesangPhoto  from '../../Images/staff/dr chesang.png'
import zipporahPhoto from '../../Images/staff/Dr Zipporah.jpeg'
import margaretPhoto from '../../Images/staff/Margaret.jpg'
import nguruPhoto    from '../../Images/staff/Dr Kenedy Nguru.jpg'

export const team = [
  {
    id: 1,
    name: 'Dr. Chesang',
    role: 'Chief Veterinarian & Founder',
    bio: 'Over 15 years transforming veterinary care in East Africa. Board-certified in small animal surgery and internal medicine.',
    image: chesangPhoto,
    imageFit: 'contain', // full-body cutout — show arms instead of cropping
    imageZoom: 1.22,
    socials: { linkedin: '#', twitter: '#' },
  },
  {
    id: 2,
    name: 'Dr. Zipporah Mwaniki',
    role: 'Veterinarian – Medicine & Feline',
    bio: 'Specialist in feline medicine and exotic pets. Known for her gentle, patient-centred approach to every animal she treats.',
    image: zipporahPhoto,
    socials: { linkedin: '#' },
  },
  {
    id: 5,
    name: 'Margaret Nyambura',
    role: 'Clinic Administrator',
    bio: 'The heart of our operations — ensuring every visit runs smoothly from the moment you book to the moment you leave.',
    image: margaretPhoto,
    socials: { linkedin: '#' },
  },
  {
    id: 6,
    name: 'Kennedy Nguru',
    role: 'Senior Laboratory Technician',
    bio: 'Expert in haematology, biochemistry, and microbiology. Processes in-house tests with consistent same-day turnaround.',
    image: nguruPhoto,
    socials: {},
  },
  {
    id: 4,
    name: 'Dr. Kennedy Kagucia',
    role: 'Veterinarian – Diagnostics',
    bio: 'Specialises in imaging and laboratory diagnostics. Committed to fast, accurate results for even the most complex cases.',
    image: null,
    socials: { linkedin: '#' },
  },
  {
    id: 7,
    name: 'Stephen Miriongi',
    role: 'Kennel Handler',
    bio: 'Devoted animal lover with 8 years of boarding experience. Ensures every boarding guest feels safe, stimulated, and loved.',
    image: null,
    socials: {},
  },
  {
    id: 8,
    name: 'Julius Nkurunziza',
    role: 'Kennel Handler',
    bio: 'Specialises in enrichment activities for boarded pets. Always ready with a treat, a toy, and a kind word.',
    image: null,
    socials: {},
  },
]

export const testimonials = [
  {
    id: 1,
    quote: 'Poseidon Vet saved my dog Kibo after an emergency. The team was calm, fast, and incredibly caring. I will never go anywhere else.',
    author: 'Aisha M.',
    location: 'Langata, Nairobi',
    rating: 5,
    avatar: 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?auto=format&fit=crop&w=80&h=80&q=80',
  },
  {
    id: 2,
    quote: 'Dr. Zipporah is an absolute gem. She took time to explain every step of my cat\'s treatment and followed up personally the next day.',
    author: 'James K.',
    location: 'Karen, Nairobi',
    rating: 5,
    avatar: 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=80&h=80&q=80',
  },
  {
    id: 3,
    quote: 'The boarding facility is spotless and the team clearly loves animals. My pups come home happy and well-exercised every time.',
    author: 'Priya S.',
    location: 'Westlands, Nairobi',
    rating: 5,
    avatar: 'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=80&h=80&q=80',
  },
]
