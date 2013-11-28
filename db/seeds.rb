# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

["Sports", "Music"].each do |r|
  EventType.create(name: r)
end

["English", "Hindi", "Telugu"].each do |r|
  EventLanguage.create(name: r)
end

@type=EventType.find(1)
@lan=EventLanguage.find(1)
results = Geocoder.search('Hyderabad, Andhra Pradesh, India')

user=User.create(email:'naresh.nookal@gmail.com',password:'nareshbabu') 
profile=Profile.create(user_id: user.id,mobile: '(949)064-4170')

=begin event = Event.create!(name:'naresh', start_at: Date.today-15000.day, end_at: Date.today-15000.day, city: results[0].city , event_type_id: @type.id, event_language_id: @lan.id, latitude: results[0].latitude, longitude: results[0].longitude, state: results[0].state, country: results[0].country, zipcode: results[0].postal_code,start_minute: 00 ,start_hour: 9 ,start_meridiem: 1,end_minute: 00 ,end_hour: 11,end_meridiem: 1, address: 'Hyderabad, Andhra Pradesh, India',content:'hii',venue:'hyd',user_id: user.id, status:1)
signed=UserSigned.create(user_id: user.id,event_id: event.id)
=end
count =Event.count
(count..(count+200)).each do |i| 
event =Event.new
event.name ="naresh#{i}"
event.start_at= Date.today+i.day
event.end_at= Date.today+(i+3).day
event.city= results[0].city
event.event_type_id= @type.id
event.event_language_id= @lan.id
event.latitude= results[0].latitude
event.longitude= results[0].longitude
event.state= results[0].state
event.country= results[0].country
event.zipcode= results[0].postal_code
event.start_minute= 00 
event.start_hour= 9 
event.start_meridiem= 1
event.end_minute= 00
event.end_hour= 11
event.end_meridiem= 1
event.address= 'Hyderabad, Andhra Pradesh, India'
event.content='hii'
event.venue='hyd'
event.user_id= user.id
event.status=1
event.avatar = File.open("#{Rails.root}/app/assets/images/sachin.jpg")
event.save!

#event = Event.create!(name:"naresh#{i}", start_at: Date.today+i.day, end_at: Date.today+(i+3).day, city: results[0].city , event_type_id: @type.id, event_language_id: @lan.id, latitude: results[0].latitude, longitude: results[0].longitude, state: results[0].state, country: results[0].country, zipcode: results[0].postal_code,start_minute: 00 ,start_hour: 9 ,start_meridiem: 1,end_minute: 00 ,end_hour: 11,end_meridiem: 1, address: 'Hyderabad, Andhra Pradesh, India',content:'hii',venue:'hyd',user_id: user.id, status:1)
#signed=UserSigned.create(user_id: user.id,event_id: event.id)
end










