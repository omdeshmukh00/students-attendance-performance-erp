import {
  PieChart,
  Pie,
  Cell,
  Tooltip,
  ResponsiveContainer
} from "recharts";

const colors = ["#2563eb","#10b981","#f59e0b","#ef4444","#6366f1"]

export default function SubjectChart({data}:any){

  return(

    <div className="bg-white p-6 rounded-xl shadow">

      <h2 className="font-bold mb-4">
        Subject Performance
      </h2>

      <ResponsiveContainer width="100%" height={300}>

        <PieChart>

          <Pie
            data={data}
            dataKey="avg"
            nameKey="subject"
            outerRadius={100}
          >
            {data.map((_:any,index:number)=>(
              <Cell key={index} fill={colors[index % colors.length]}/>
            ))}
          </Pie>

          <Tooltip/>

        </PieChart>

      </ResponsiveContainer>

    </div>

  )

}